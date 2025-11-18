<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Modules\BankManager\Models\AccountBalance;
use App\Modules\BankManager\Models\Transaction;
use App\Modules\BankManager\Models\TransactionDescription;
use App\Modules\BankManager\Models\OperationCategory; 

use App\Modules\BankManager\Models\Investments\Investment;


class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::with('histories')->orderBy('created_at', 'desc')->get();
        $accountBalance = AccountBalance::firstOrCreate(['id' => 1], ['balance' => 0]);

        // Cálculos de valores
        $totalInvested = Investment::sum('initial_amount');
        $currentTotalValue = Investment::sum('current_amount');
        $totalProfitLoss = $currentTotalValue - $totalInvested;

        return view('bankmanager::investments.index', compact('investments', 'accountBalance', 'totalInvested', 'currentTotalValue', 'totalProfitLoss'));
    }

    public function storeInvestment(Request $request)
    {
        // Validate and process the investment data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'platform' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'initial_amount' => 'required|numeric|min:0',
        ]);

        // Cria o investimento
        $investment = Investment::create($validatedData);

        $account = AccountBalance::findOrFail(1);

        if ($account->balance < $validatedData['initial_amount']) {
            return redirect()->back()->with('error', 'Saldo insuficiente para realizar o investimento.');
        } else {
            // Deduzir o valor do investimento do saldo da conta
            $account->balance -= $validatedData['initial_amount'];
            $account->save();

            // Cria a transação principal
            $transaction = Transaction::create([
                // Futuramente, permitir selecionar a conta
                'account_balance_id' => 1,
                'operation_category_id' => OperationCategory::where('name', 'Investimentos_Expenses')->first()->id,
                'amount' => $validatedData['initial_amount'],
            ]);
           
        }

        return redirect()->back()->with('success', 'Investimento adicionado com sucesso!');
    }

    public function editInvestment(Request $request, Investment $investment)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'platform' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $oldName = $investment->name;

        // Atualiza o investimento
        $investment->update($validatedData);

        // Atualiza descrições ligadas às transações deste investimento
        $categories = ['Investimentos_Expenses', 'Investimentos_Income'];

        foreach ($categories as $categoryName) {
            $category = OperationCategory::where('name', $categoryName)->first();

            if ($category) {
                $transactions = Transaction::where('operation_category_id', $category->id)
                    ->whereHas('description', function ($q) use ($oldName, $categoryName) {
                        $q->where('description', "{$oldName} ({$categoryName})");
                    })
                    ->get();

                foreach ($transactions as $transaction) {
                    $transaction->description->update([
                        'description' => "{$validatedData['name']} ({$categoryName})",
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Investimento editado com sucesso!');
    }

    public function deleteInvestment(Request $request, Investment $investment)
    {
        $request->validate([
            'resgate' => 'required|in:return,delete',
        ]);

        $valorInvestido = $investment->current_amount;

        if ($request->resgate === 'return' && $valorInvestido > 0) {
            // Atualiza saldo na conta
            $balance = AccountBalance::firstOrFail();

            $balance->balance += $valorInvestido;
            $balance->save();

            // Registra a transação de retorno
            $category = OperationCategory::where('name', 'Investimentos_Income')->first();

            $transaction = Transaction::create([
                'operation_category_id' => $category->id,
                'amount' => $valorInvestido,
            ]);

           
        }

        // Marca todas as descrições antigas como finalizadas
        $categories = ['Investimentos_Expenses', 'Investimentos_Income'];

        foreach ($categories as $categoryName) {
            TransactionDescription::where('description', "{$investment->name} ({$categoryName})")->update([
                'description' => "{$investment->name} ({$categoryName} - FINALIZADO)",
            ]);
        }

        // Soft delete do investimento (mantém histórico)
        $investment->delete();

        return redirect()->back()->with('success', 'Investimento removido com sucesso!');
    }

    public function applyCashflow(Request $request, Investment $investment)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:aporte,retirada',
            'descricao' => 'nullable|string|max:1000',
        ]);

        $valor = $request->valor;
        $tipo = $request->tipo;

        $account = AccountBalance::findOrFail(1);

        if ($tipo === 'aporte') {
            // Verifica saldo
            if ($account->balance < $valor) {
                return redirect()->back()->with('danger', 'Saldo insuficiente na conta principal para realizar o aporte.');
            }

            // Atualiza saldos
            $account->balance -= $valor;
            $investment->current_amount += $valor;

            // Cria transação principal
            $transaction = Transaction::create([
                'operation_category_id' => OperationCategory::where('name', 'Investimentos_Expenses')->first()->id,
                'amount' => $valor,
            ]);

            // Cria descrição vinculada
            TransactionDescription::create([
                'transaction_id' => $transaction->id,
                'description' => "{$investment->name} (Investimentos_Expenses)",
            ]);
        } else {
            // Retirada
            if ($investment->current_amount < $valor) {
                return redirect()->back()->with('danger', 'Saldo insuficiente no investimento para realizar a retirada.');
            }

            $account->balance += $valor;
            $investment->current_amount -= $valor;

            $transaction = Transaction::create([
                // Futuramente, permitir selecionar a conta
                'account_balance_id' => 1,
                'operation_category_id' => OperationCategory::where('name', 'Investimentos_Income')->first()->id,
                'amount' => $valor,
            ]);

            TransactionDescription::create([
                'transaction_id' => $transaction->id,
                'description' => "{$investment->name} (Investimentos_Income)",
            ]);
        }

        $account->save();
        $investment->save();

        return redirect()->back()->with('success', 'Movimentação registrada com sucesso!');
    }

    public function applyMarketUpdate(Investment $investment, Request $request)
    {
        $data = $request->validate([
            'valor_mercado' => 'required|numeric|min:0',
            'reference_date' => 'required|date',
        ]);

        DB::transaction(function () use ($investment, $data) {
            $previous = $investment->histories()->orderByDesc('reference_date')->first();

            $base = $previous->amount ?? $investment->initial_amount;

            $variation = $data['valor_mercado'] - $base;
            $percentage = $base > 0 ? ($variation / $base) * 100 : null;

            // Atualiza o valor atual do investimento
            $investment->update([
                'current_amount' => $data['valor_mercado'],
            ]);

            // Cria ou atualiza o histórico do mês/dia (evita erro da unique key)
            $investment->histories()->updateOrCreate(
                ['reference_date' => $data['reference_date']],
                [
                    'amount' => $data['valor_mercado'],
                    'variation' => $variation,
                    'percentage' => $percentage,
                ],
            );
        });

        return back()->with('success', 'Valor de mercado atualizado com sucesso!');
    }
}

<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Modules\BankManager\Models\AccountBalance;
use App\Modules\BankManager\Models\Transaction;
use App\Modules\BankManager\Models\TransactionDescription;
use App\Modules\BankManager\Models\OperationCategory;
use App\Modules\BankManager\Models\OperationSubCategory;
use App\Modules\BankManager\Models\Investments\Investment;

use Illuminate\Support\Facades\Auth;


class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::with('histories')->orderBy('created_at', 'desc')->get();
        $user = Auth::user();

        $accountBalance = AccountBalance::where('user_id', $user->id)->get();

        // Cálculos de valores
        $totalInvested = Investment::sum('initial_amount');
        $currentTotalValue = Investment::sum('current_amount');
        $totalProfitLoss = $currentTotalValue - $totalInvested;

        return view('bankmanager::investments.index', compact('investments', 'accountBalance', 'totalInvested', 'currentTotalValue', 'totalProfitLoss'));
    }

    public function storeInvestment(Request $request)
    {
        // Validação
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'platform'        => 'required|string|max:255',
            'type'            => 'required|string|max:255',
            'initial_amount'  => 'required|numeric|min:0.01',
            'account_balance_id' => 'required|exists:app_bank_manager_account_balances,id',
        ]);

        // Conta selecionada
        $account = AccountBalance::findOrFail($validated['account_balance_id']);
        // Verifica saldo suficiente
        if ($account->current_balance < $validated['initial_amount']) {
            return redirect()->back()
                ->with('error', 'Saldo insuficiente na conta selecionada.');
        }

        // 1. Criar o investimento
        $investment = Investment::create([
            'name'           => $validated['name'],
            'platform'       => $validated['platform'],
            'type'           => $validated['type'],
            'initial_amount' => $validated['initial_amount'],
        ]);

        // 2. Deduzir saldo
        $account->current_balance -= $validated['initial_amount'];
        $account->save();

        // 3. Criar transação
        $category = OperationCategory::where('name', 'Investimentos')->firstOrFail();
        $subcategory = OperationSubCategory::where([
            'name' => 'Aporte inicial',
            'operation_category_id' => $category->id,
        ])->firstOrFail();

        Transaction::create([
            'description'               => "Aporte inicial - {$investment->name}",
            'account_balance_id'        => $account->id,
            'operation_type_id'         => 2, // expense
            'operation_sub_category_id' => $subcategory->id,
            'amount'                    => $validated['initial_amount'],
        ]);

        return redirect()->back()->with('success', 'Investimento criado com sucesso!');
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

        $action = $request->resgate;
        $valorInvestido = $investment->current_amount;

        /**
         * 1) APENAS APAGAR O INVESTIMENTO
         */
        if ($action === 'delete') {
            $investment->delete();
            return back()->with('success', 'Investimento excluído com sucesso.');
        }

        /**
         * 2) DEVOLVER O VALOR PARA A CONTA E DEPOIS EXCLUIR
         */
        if ($action === 'return') {

            // Seleção da conta é obrigatória
            if (!$request->filled('account_balance_id')) {
                return back()->with('error', 'Selecione uma conta para receber o valor.');
            }

            $conta = AccountBalance::findOrFail($request->account_balance_id);

            // 1. Devolver saldo
            if ($valorInvestido > 0) {
                $conta->current_balance += $valorInvestido;
                $conta->save();
            }

            // 2. Categoria/Subcategoria do retorno de investimento
            $categoria = OperationCategory::where('name', 'Investimentos')->firstOrFail();
            $subcategoria = OperationSubCategory::where([
                'name' => 'Retorno',
                'operation_category_id' => $categoria->id,
            ])->firstOrFail();

            // 3. Criar transação de retorno
            Transaction::create([
                'description'               => "{$investment->name} (Investimento Cancelado)",
                'account_balance_id'        => $conta->id,
                'operation_type_id'         => 1, // Income
                'operation_sub_category_id' => $subcategoria->id,
                'amount'                    => $valorInvestido,
            ]);

            // 4. Finalmente apagar o investimento
            $investment->delete();

            return back()->with('success', 'Investimento excluído e valor devolvido com sucesso!');
        }

        return back()->with('error', 'Ação inválida.');
    }

    public function applyCashflow(Request $request, Investment $investment)
    {
        // 1. VALIDAÇÃO
        $validated = $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'tipo'  => 'required|in:aporte,retirada',
            'account_balance_id' => 'required|exists:app_bank_manager_account_balances,id',
        ]);

        $valor = $validated['valor'];
        $tipo  = $validated['tipo'];

        // Conta selecionada
        $conta = AccountBalance::findOrFail($validated['account_balance_id']);


        // Aporte
        if ($tipo === 'aporte') {

            if ($conta->current_balance < $valor) {
                return back()->with('danger', 'Saldo insuficiente na conta selecionada.');
            }

            // Atualiza valores
            $conta->current_balance -= $valor;
            $investment->current_amount += $valor;

            // Categoria/Subcategoria do aporte
            $categoria = OperationCategory::where('name', 'Investimentos')->firstOrFail();
            $subcategoria = OperationSubCategory::where([
                'name' => 'Aporte inicial',
                'operation_category_id' => $categoria->id,
            ])->firstOrFail();

            // Cria transação
            Transaction::create([
                'description'               => "Aporte - {$investment->name}",
                'account_balance_id'        => $conta->id,
                'operation_type_id'         => 2, // expense
                'operation_sub_category_id' => $subcategoria->id,
                'amount'                    => $valor,
            ]);
        }

        // Retirada
        else if ($tipo === 'retirada') {

            if ($investment->current_amount < $valor) {
                return back()->with('danger', 'O investimento não possui esse valor para retirada.');
            }

            // Atualiza valores
            $investment->current_amount -= $valor;
            $conta->current_balance += $valor;

            // Categoria/Subcategoria da retirada
            $categoria = OperationCategory::where('name', 'Investimentos')->firstOrFail();
            $subcategoria = OperationSubCategory::where([
                'name' => 'Retirada',
                'operation_category_id' => $categoria->id,
            ])->firstOrFail();

            // Cria transação
            Transaction::create([
                'description'               => "Retirada - {$investment->name}",
                'account_balance_id'        => $conta->id,
                'operation_type_id'         => 1, // income
                'operation_sub_category_id' => $subcategoria->id,
                'amount'                    => $valor,
            ]);
        }

        // Salva tudo
        $conta->save();
        $investment->save();

        return back()->with('success', 'Movimentação registrada com sucesso!');
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

<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Modules\BankManager\Models\Debts\Debt;
use App\Modules\BankManager\Models\Debts\DebtInstallment;
use App\Modules\BankManager\Models\Transaction;
use App\Modules\BankManager\Models\TransactionDescription;
use App\Modules\BankManager\Models\OperationCategory;
use App\Modules\BankManager\Models\AccountBalance;
use App\Modules\BankManager\Models\OperationSubCategory;

use Carbon\Carbon;

class DebtsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $debts = Debt::with('installmentsList')->orderBy('created_at', 'desc')->get();

        $user = Auth::user();

        $accountBalance = AccountBalance::where('user_id', $user->id)->get();

        // Dividas e parcelas
        $totalDebt = Debt::sum('total_amount');
        $paidAmount = DebtInstallment::whereNotNull('paid_at')->sum('amount');
        $pendingAmount = DebtInstallment::whereNull('paid_at')->sum('amount');


        return view('bankmanager::debts/index', compact('debts', 'accountBalance', 'totalDebt', 'paidAmount', 'pendingAmount'));
    }

    public function storeDebt(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'total_amount' => 'required|numeric|min:0.01',
            'installments' => 'required|integer|min:1',
        ]);

        // Cria a dívida principal
        $debt = Debt::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'total_amount' => $validated['total_amount'],
            'installments' => $validated['installments'],
            'start_date' => now()->toDateString(),
        ]);

        // Valor base por parcela
        $installmentAmount = round($validated['total_amount'] / $validated['installments'], 2);
        $totalCreated = 0;
        $startDate = now();

        for ($i = 1; $i <= $validated['installments']; $i++) {
            // Próximo vencimento
            $dueDate = $startDate->copy()->addMonthsNoOverflow($i);

            // Ajuste final na última parcela (evita cêntimos perdidos)
            if ($i === $validated['installments']) {
                $installmentAmount = $validated['total_amount'] - $totalCreated;
            }

            DebtInstallment::create([
                'debt_id' => $debt->id,
                'installment_number' => $i,
                'amount' => $installmentAmount,
                'due_date' => $dueDate->toDateString(),
            ]);

            $totalCreated += $installmentAmount;
        }

        return redirect()->back()->with('success', 'Dívida registrada com parcelas mensais!');
    }


    public function markInstallmentAsPaid(DebtInstallment $installmentId, Request $request)
    {
        // Marca a parcela como paga
        $installmentId->paid_at = now();
        $installmentId->save();

        // Categoria e subcategoria corretas
        $category = OperationCategory::where('name', 'Debitos')->firstOrFail();
        $subcategory = OperationSubCategory::where([
            'name' => 'Dividas',
            'operation_category_id' => $category->id
        ])->firstOrFail();

        // Transação de devolução
        Transaction::create([
            'description' => "{$installmentId->debt->name} (Divida)",
            'account_balance_id' => $request->account_balance_id,
            'operation_type_id' => 2, // expense
            'operation_sub_category_id' => $subcategory->id,
            'amount' => $installmentId->amount,
        ]);
        
        // Atualizar saldo
        $account = AccountBalance::findOrFail($request->account_balance_id);
        $account->current_balance -= $installmentId->amount;
        $account->save();

        return redirect()->back()->with('success', 'Parcela paga com sucesso, Parabéns!');
    }

    public function editDebt(Request $request, Debt $debt)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'total_amount' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    function ($attribute, $value, $fail) use ($debt) {
                        $alreadyPaid = $debt->installmentsList->whereNotNull('paid_at')->sum('amount');
                        if ($value < $alreadyPaid) {
                            $fail('O valor total não pode ser menor que o já pago (€ ' . number_format($alreadyPaid, 2) . ')');
                        }
                    },
                ],
                'installments' => 'required|integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        }

        try {
            $alreadyPaid = $debt->installmentsList->whereNotNull('paid_at')->sum('amount');
            $paidCount = $debt->installmentsList->whereNotNull('paid_at')->count();

            $newTotal = $validated['total_amount'];
            $newInstallmentsCount = $validated['installments'];

            if ($newTotal < $alreadyPaid) {
                return redirect()->back()->with('danger', 'O valor total não pode ser menor que o já pago');
            }

            $oldName = $debt->name;

            // Atualiza os dados principais
            $debt->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'total_amount' => $newTotal,
                'installments' => $newInstallmentsCount,
            ]);


            // Remove parcelas pendentes antigas
            $debt->installmentsList()->whereNull('paid_at')->delete();

            // Recria as parcelas restantes com novas datas
            $remainingAmount = $newTotal - $alreadyPaid;
            $installmentValue = round($remainingAmount / $newInstallmentsCount, 2);

            // Define a data inicial: um mês após a última parcela paga ou após a última vencida
            $lastPaid = $debt->installmentsList()->whereNotNull('paid_at')->latest('due_date')->first();
            $startDate = $lastPaid
                ? Carbon::parse($lastPaid->due_date)->copy()->addMonthNoOverflow()
                : now()->addMonthNoOverflow();

            $totalCreated = 0;

            for ($i = 1; $i <= $newInstallmentsCount; $i++) {
                $dueDate = $startDate->copy()->addMonthsNoOverflow($i - 1);

                // Corrige diferença de cêntimos na última
                if ($i === $newInstallmentsCount) {
                    $installmentValue = $remainingAmount - $totalCreated;
                }

                DebtInstallment::create([
                    'debt_id' => $debt->id,
                    'installment_number' => $paidCount + $i,
                    'amount' => $installmentValue,
                    'due_date' => $dueDate->toDateString(),
                    'paid_at' => null,
                ]);

                $totalCreated += $installmentValue;
            }

            return redirect()->back()->with('success', 'Dívida atualizada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Erro ao atualizar a dívida: ' . $e->getMessage());
        }
    }


    public function deleteDebt(Request $request, Debt $debt)
    {
        $totalPaid = $debt->installmentsList()->whereNotNull('paid_at')->sum('amount');

        // Se a opção for devolver o valor à conta
        if ($request->refund_option === 'refund' && $totalPaid > 0) {
            $balance = AccountBalance::firstOrCreate(['id' => 1]);
            $balance->balance += $totalPaid;
            $balance->save();

            $category = OperationCategory::where('name', 'Dívidas_Income')->first();
            if ($category) {
                $transaction = Transaction::create([
                    'operation_category_id' => $category->id,
                    'amount' => $totalPaid,
                ]);

              
            }
        }

       

        // Exclui parcelas e dívida
        $debt->installmentsList()->delete();
        $debt->delete();

        return redirect()->back()->with('success', 'Dívida excluída com sucesso.');
    }
}

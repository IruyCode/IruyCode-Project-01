<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Modules\BankManager\Models\FixedExpenses\FixedExpense;
use App\Modules\BankManager\Models\FixedExpenses\FixedExpensePayment;
use App\Modules\BankManager\Models\OperationCategory;
use App\Modules\BankManager\Models\OperationSubCategory;
use App\Modules\BankManager\Models\Transaction;
use App\Modules\BankManager\Models\AccountBalance;

use Carbon\Carbon;

class FixedExpenseController extends Controller
{
    /**
     * Criar uma nova despesa fixa.
     */
    public function createfixedExpense(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'amount'     => 'required|numeric|min:0.01',
            'due_day'    => 'required|integer|min:1|max:31',
        ]);


        // 1) Localizar a categoria correta
        $category = OperationCategory::where('name', 'Despesas-Fixas')->firstOrFail();

        // 2) Localizar a subcategoria padrão
        $subCategory = OperationSubCategory::where([
            'name' => 'Pagar Despesa',
            'operation_category_id' => $category->id
        ])->firstOrFail();

        // 3) Criar a despesa fixa usando o subcategory_id correto
        FixedExpense::create([
            'name'                     => $validated['name'],
            'amount'                   => $validated['amount'],
            'due_day'                  => $validated['due_day'],
            'operation_sub_category_id' => $subCategory->id,
        ]);

        return back()->with('success', 'Despesa fixa criada com sucesso!');
    }


    /**
     * Excluir despesa fixa (soft delete ou delete normal)
     */
    public function destroyExpense(FixedExpense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Despesa fixa excluída com sucesso!');
    }

    /**
     * Marcar despesas selecionadas como pagas.
     */
    public function markAsPaidFixedExpense(Request $request)
    {
        $validated = $request->validate([
            'expenses'           => 'required|array|min:1',
            'expenses.*'         => 'exists:app_bank_manager_fixed_expenses,id',
            'account_balance_id' => 'required|exists:app_bank_manager_account_balances,id',
        ]);


        $conta = AccountBalance::findOrFail($validated['account_balance_id']);
        $today = Carbon::today();



        // Categoria e Subcategoria fixas
        $category = OperationCategory::where('name', 'Despesas-Fixas')->firstOrFail();
        $subcategory = OperationSubCategory::where([
            'operation_category_id' => $category->id,
            'name' => 'Pagar Despesa',
        ])->firstOrFail();

        foreach ($validated['expenses'] as $expenseId) {

            $expense = FixedExpense::findOrFail($expenseId);

            // Evitar pagamento duplicado no mesmo mês
            $alreadyPaid = FixedExpensePayment::where('fixed_expense_id', $expenseId)
                ->where('year', $today->year)
                ->where('month', $today->month)
                ->exists();

            if ($alreadyPaid) {
                continue;
            }

            // Registrar pagamento
            FixedExpensePayment::create([
                'fixed_expense_id' => $expenseId,
                'paid_at'          => $today,
                'year'             => $today->year,
                'month'            => $today->month,
            ]);

            // Criar transação
            Transaction::create([
                'description'               => "{$expense->name} (Despesa Fixa)",
                'account_balance_id'        => $conta->id,
                'operation_type_id'         => 2, // expense
                'operation_sub_category_id' => $subcategory->id,
                'amount'                    => $expense->amount,
            ]);

            // Atualizar saldo da conta
            $conta->current_balance -= $expense->amount;
            $conta->save();
        }

        return back()->with('success', 'Despesas fixas marcadas como pagas com sucesso!');
    }
}

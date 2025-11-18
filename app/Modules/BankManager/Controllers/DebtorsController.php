<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\BankManager\Models\Debtors\Debtor;
use App\Modules\BankManager\Models\Debtors\DebtorEdit;

use App\Modules\BankManager\Models\Transaction;
use App\Modules\BankManager\Models\TransactionDescription;
use App\Modules\BankManager\Models\OperationCategory;
use App\Modules\BankManager\Models\AccountBalance;
use App\Modules\BankManager\Models\OperationSubCategory;
use Illuminate\Support\Facades\Auth;


class DebtorsController extends Controller
{
    /**
     * Display a listing of debtors.
     */
    public function index()
    {
        $user = Auth::user();
        $debtors = Debtor::with('edits')->orderBy('created_at', 'desc')->get();
        $accountBalance = AccountBalance::where('user_id', $user->id)->get();
        $totalAmountOwed = Debtor::sum('amount');
        $totalAmountPaid = Debtor::where('is_paid', true)->sum('amount');
        $totalPendingAmount = Debtor::where('is_paid', false)->sum('amount');

        return view('bankmanager::debtors/index', compact('debtors', 'accountBalance', 'totalAmountOwed', 'totalAmountPaid', 'totalPendingAmount'));
    }

    /**
     * Store a newly created debtor in storage.
     */
    public function storeDebtor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
        ]);

        // Verifica se existe um devedor com o mesmo nome
        $exists = Debtor::where('name', $validated['name'])->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withErrors(['name' => 'Já existe um devedor com este nome.'])
                ->withInput();
        }

        Debtor::create($validated);


        return redirect()->back()->with('success', 'Devedor criado com sucesso.');
    }

    /**
     * Edit debtor in storage.
     */
    public function editDebtor(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
        ]);

        $debtor = Debtor::findOrFail($id);

        // Verifica se o nome já está em uso por outro devedor
        $nameExists = Debtor::where('name', $validated['name'])->where('id', '!=', $debtor->id)->exists();

        if ($nameExists) {
            return redirect()
                ->back()
                ->withErrors(['name' => 'Já existe outro devedor com este nome.'])
                ->withInput();
        }

        // Guarda os valores antigos ANTES de atualizar
        $oldAmount = $debtor->amount;
        $oldDueDate = $debtor->due_date->format('Y-m-d');

        // Detecta se houve alteração
        $amountChanged = $oldAmount != $validated['amount'];
        $dueDateChanged = $oldDueDate != $validated['due_date'];

        // Se valor ou data mudaram, exige motivo e grava histórico
        if ($amountChanged || $dueDateChanged) {
            if (empty($validated['reason'])) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'reason' => 'O motivo da alteração é obrigatório quando o valor ou a data são modificados.',
                    ]);
            }

            // Cria registro de histórico ANTES do update
            DebtorEdit::create([
                'debtor_id' => $debtor->id,
                'old_amount' => $oldAmount,
                'new_amount' => $validated['amount'],
                'old_due_date' => $oldDueDate,
                'new_due_date' => $validated['due_date'],
                'reason' => $validated['reason'],
            ]);
        }

        // Atualiza o devedor (após registrar histórico)
        $debtor->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
        ]);

        return redirect()->back()->with('success', 'Devedor atualizado com sucesso.');
    }

    /**
     * Remove Debtor from storage.
     */
    public function deleteDebtor(string $id)
    {
        $debtor = Debtor::findOrFail($id);

        // Apaga histórico associado
        DebtorEdit::where('debtor_id', $debtor->id)->delete();

        // Apenas exclui — sem atualizar transações
        $debtor->delete();

        return redirect()->back()->with('success', 'Devedor apagado com sucesso.');
    }


    /**
     * Conclude the specified debtor and create a return transaction.
     */
    public function concludeDebtor(Debtor $debtor, Request $request)
    {
        // Marca como pago
        $debtor->update([
            'is_paid' => true,
            'paid_at' => now()
        ]);

        // Categoria e subcategoria corretas
        $category = OperationCategory::where('name', 'Debitos')->firstOrFail();
        $subcategory = OperationSubCategory::where([
            'name' => 'Devedores',
            'operation_category_id' => $category->id
        ])->firstOrFail();

        // Transação de devolução
        Transaction::create([
            'description' => "{$debtor->name} (Devolução)",
            'account_balance_id' => $request->account_balance_id,
            'operation_type_id' => 1, // income
            'operation_sub_category_id' => $subcategory->id,
            'amount' => $debtor->amount,
        ]);

        // Atualizar saldo
        $account = AccountBalance::findOrFail($request->account_balance_id);
        $account->current_balance += $debtor->amount;
        $account->save();

        return redirect()->back()->with('success', 'Devedor concluído e valor devolvido com sucesso.');
    }
}

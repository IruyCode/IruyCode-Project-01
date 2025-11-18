<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\BankManager\Models\Goals\FinancialGoals;
use App\Modules\BankManager\Models\Goals\GoalTransaction;

use App\Modules\BankManager\Models\AccountBalance;
use App\Modules\BankManager\Models\OperationCategory;
use App\Modules\BankManager\Models\Transaction;
use App\Modules\BankManager\Models\TransactionDescription;
use App\Modules\BankManager\Models\OperationSubCategory;

use Illuminate\Support\Facades\Auth;


class GoalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $accountBalance = AccountBalance::where('user_id', $user->id)->get();
        $goals = FinancialGoals::where('is_completed', false)->orderBy('deadline', 'asc')->get();
        $completedGoals = FinancialGoals::where('is_completed', true)->orderBy('completed_at', 'desc')->get();

        return view('bankmanager::goals.index', compact('goals', 'completedGoals', 'accountBalance'));
    }

    // Funcao para criar uma nova meta
    public function storeFinancialGoal(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0.01',
            'deadline' => 'required|date|after:today',
            'current_amount' => 'nullable|numeric|min:0',
        ]);

        // Cria a meta financeira
        FinancialGoals::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'target_amount' => $validated['target_amount'],
            'current_amount' => $validated['current_amount'] ?? 0,
            'deadline' => $validated['deadline'],
        ]);

        // Se o usuário já colocou um valor inicial, registrar no saldo e na transação
        if (!empty($validated['current_amount']) && $validated['current_amount'] > 0) {

            $user = Auth::user();

            // Cria a transação correspondente
            $category = OperationCategory::where('name', 'Metas')->firstOrFail();
            $subcategory = OperationSubCategory::where([
                'name' => 'Metas Ativas',
                'operation_category_id' => $category->id
            ])->firstOrFail();

            // Atualiza o saldo da conta
            $account = AccountBalance::where('user_id', $user->id)->first();
            $account->current_balance -= $validated['current_amount'];
            $account->save();

            Transaction::create([
                'description' => "{$validated['name']} (Metas Ativas)",
                'account_balance_id' => $account->id,
                'operation_type_id' => 2, // income
                'operation_sub_category_id' => $subcategory->id,
                'amount' => $validated['current_amount'],
            ]);
        }

        return redirect()->back()->with('success', 'Nova meta criada com sucesso! Boa sorte!');
    }

    // Atualiza a Meta Financeira
    public function updateGoal(Request $request, FinancialGoals $goal)
    {
        // valida apenas se o campo vier preenchido
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'target_amount' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date',
        ]);

        try {
            $oldName = $goal->name;

            // substitui apenas o que foi enviado, mantendo o resto igual
            $goal->update([
                'name' => $validatedData['name'] ?? $goal->name,
                'target_amount' => $validatedData['target_amount'] ?? $goal->target_amount,
                'deadline' => $validatedData['deadline'] ?? $goal->deadline,
            ]);

            // atualiza descrições relacionadas
            $categories = ['Metas_Expenses', 'Metas_Income'];

            foreach ($categories as $categoryName) {
                TransactionDescription::where('description', "{$oldName} ({$categoryName})")->update([
                    'description' => "{$goal->name} ({$categoryName})",
                ]);
            }

            return redirect()->back()->with('success', 'Meta atualizada com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao atualizar meta: ' . $e->getMessage());
        }
    }


    public function destroyGoal(FinancialGoals $goal, Request $request)
    {
        dd($request->all());

        try {
            $resgate = $request->input('resgate');
            $valorMeta = $goal->current_amount;

            // Se a opção for devolver o dinheiro, adiciona à Conta Ordem e cria uma transação
            if ($resgate === 'return' && $valorMeta > 0) {
                $account = AccountBalance::firstOrCreate(['id' => 1], ['balance' => 0]);
                $account->balance += $valorMeta;
                $account->save();

                $category = OperationCategory::where('name', 'Metas_Income')->first();
                $transaction = Transaction::create([
                    'operation_category_id' => $category->id,
                    'amount' => $valorMeta,
                ]);

                // Cria descrição dessa transação de resgate
                TransactionDescription::create([
                    'transaction_id' => $transaction->id,
                    'description' => "{$goal->name} (Metas_Income - FINALIZADA)",
                ]);
            }

            // Atualiza descrições antigas para indicar que a meta foi finalizada
            $categories = ['Metas_Expenses', 'Metas_Income'];
            foreach ($categories as $categoryName) {
                TransactionDescription::where('description', "{$goal->name} ({$categoryName})")->update([
                    'description' => "{$goal->name} ({$categoryName} - FINALIZADA)",
                ]);
            }

            // Soft delete (mantém histórico)
            $goal->delete();

            return redirect()->back()->with('success', 'Meta excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir meta: ' . $e->getMessage());
        }
    }

    // Finaliza uma meta
    public function finishGoal($goal)
    {
        $goal = FinancialGoals::findOrFail($goal);
        $goal->is_completed = true;
        $goal->completed_at = now();
        $goal->save();

        return redirect()->back()->with('success', 'Meta financeira concluida com sucesso!');
    }

    public function adjustGoalValue(FinancialGoals $goal, Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:add,remove',
        ]);

        // Definir a categoria baseada no tipo da operação
        $categoryName = $validated['type'] === 'add' ? 'Metas_Expenses' : 'Metas_Income';

        $category = OperationCategory::with('operationType')->where('name', $categoryName)->firstOrFail();

        // Busca o saldo atual
        $balance = AccountBalance::firstOrCreate(['id' => 1]);

        // Verificação 1: Se for ADD (tirar da conta), verifica se há saldo suficiente
        if ($validated['type'] === 'add' && $balance->balance < $validated['amount']) {
            return redirect()
                ->back()
                ->withErrors(['amount' => 'Saldo insuficiente na conta principal para essa operação.']);
        }

        // Verificação 2: Se for REMOVE (tirar da meta), verifica se há saldo suficiente na meta
        if ($validated['type'] === 'remove' && $goal->current_amount < $validated['amount']) {
            return redirect()
                ->back()
                ->withErrors(['amount' => 'A meta não possui esse valor para ser removido.']);
        }

        // Cria a transação principal
        Transaction::create([
            'account_balance_id' => $balance->id,
            'operation_category_id' => $category->id,
            'amount' => $validated['amount'],
        ]);

        // Atualiza o saldo da conta
        if ($category->operationType->operation_type === 'income') {
            $balance->balance += $validated['amount'];
        } elseif ($category->operationType->operation_type === 'expense') {
            $balance->balance -= $validated['amount'];
        }
        $balance->save();

        // Atualiza o valor da meta
        if ($validated['type'] === 'add') {
            $goal->current_amount += $validated['amount'];
        } else {
            $goal->current_amount -= $validated['amount'];
        }
        $goal->save();

        // Cria o registro no histórico da meta
        GoalTransaction::create([
            'goal_id' => $goal->id,
            'type' => $validated['type'] === 'add' ? 'aporte' : 'retirada',
            'amount' => $validated['amount'],
            'note' => 'Movimentação registrada automaticamente',
            'performed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Valor da meta atualizado com sucesso!');
    }
}

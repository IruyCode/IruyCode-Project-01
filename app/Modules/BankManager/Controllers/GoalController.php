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
        // Valida apenas o que for enviado
        $validated = $request->validate([
            'name'          => 'nullable|string|max:255',
            'target_amount' => 'nullable|numeric|min:0',
            'deadline'      => 'nullable|date',
        ]);

        // Atualiza somente os campos enviados
        $goal->update([
            'name'          => $validated['name']          ?? $goal->name,
            'target_amount' => $validated['target_amount'] ?? $goal->target_amount,
            'deadline'      => $validated['deadline']      ?? $goal->deadline,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Meta atualizada com sucesso!');
    }


    public function destroyGoal(FinancialGoals $goal, Request $request)
    {
        $action = $request->input('resgate');
        $valorAtual = $goal->current_amount;

        /**
         * 1) APENAS APAGAR A META
         */
        if ($action == 'delete') {
            $goal->delete();
            return back()->with('success', 'Meta excluída com sucesso.');
        }

        /**
         * 2) DEVOLVER VALOR PARA A CONTA
         */
        if ($action == 'return') {

            //Verificar a conta selecionada
            if (!$request->filled('account_balance_id')) {
                return back()->with('error', 'Selecione uma conta para receber o valor.');
            }

            $conta = AccountBalance::findOrFail($request->account_balance_id);

            // 1. Devolver o valor
            if ($valorAtual > 0) {
                $conta->current_balance += $valorAtual;
                $conta->save();
            }

            // 2. Categoria/Subcategoria corretas
            $categoria = OperationCategory::where('name', 'Metas')->firstOrFail();
            $subcategoria = OperationSubCategory::where([
                'name' => 'Metas Ativas',
                'operation_category_id' => $categoria->id,
            ])->firstOrFail();

            // 3. Criar a transação
            Transaction::create([
                'description'               => "{$goal->name} (Meta Cancelada)",
                'account_balance_id'        => $conta->id,
                'operation_type_id'         => 1, // Income 
                'operation_sub_category_id' => $subcategoria->id,
                'amount'                    => $valorAtual,
            ]);

            // 4. Só agora apagamos a meta
            $goal->delete();

            return back()->with('success', 'Meta excluída e valor devolvido com sucesso.');
        }


        return back()->with('error', 'Ação inválida.');
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
        // 1. Validação
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:add,remove',
            'account_balance_id' => 'required|exists:app_bank_manager_account_balances,id',
        ]);

        $amount = $validated['amount'];
        $type   = $validated['type'];

        // 2. Conta selecionada
        $conta = AccountBalance::findOrFail($validated['account_balance_id']);

        // 3. Regras de negócio
        if ($type === 'add') {
            // Tirar da conta → Aumentar meta
            if ($conta->current_balance < $amount) {
                return back()->withErrors(['amount' => 'Saldo insuficiente na conta selecionada.']);
            }
        }

        if ($type === 'remove') {
            // Tirar da meta → Devolver à conta
            if ($goal->current_amount < $amount) {
                return back()->withErrors(['amount' => 'A meta não possui esse valor disponível.']);
            }
        }

        // 4. Categoria / Subcategoria
        // (Tu ajustas estes nomes conforme tua estrutura real)
        $categoria = OperationCategory::where('name', 'Metas')->firstOrFail();

        $subcategoriaName = $type === 'add' ? 'Aporte' : 'Retirada';

        $subcategoria = OperationSubCategory::where([
            'name' => $subcategoriaName,
            'operation_category_id' => $categoria->id,
        ])->firstOrFail();

        // 5. Criar transação
        $operationTypeId = $type === 'add'
            ? 2 // expense
            : 1; // income

        Transaction::create([
            'description'               => "{$goal->name} - Ajuste de Meta ({$subcategoriaName})",
            'account_balance_id'        => $conta->id,
            'operation_type_id'         => $operationTypeId,
            'operation_sub_category_id' => $subcategoria->id,
            'amount'                    => $amount,
        ]);

        // 6. Atualizar conta
        if ($type === 'add') {
            $conta->current_balance -= $amount;
        } else {
            $conta->current_balance += $amount;
        }
        $conta->save();

        // 7. Atualizar meta
        if ($type === 'add') {
            $goal->current_amount += $amount;
        } else {
            $goal->current_amount -= $amount;
        }
        $goal->save();

        // 8. Histórico
        GoalTransaction::create([
            'goal_id'       => $goal->id,
            'type'          => $type === 'add' ? 'aporte' : 'retirada',
            'amount'        => $amount,
            'note'          => 'Movimentação registrada automaticamente',
            'performed_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Valor da meta atualizado com sucesso!');
    }
}

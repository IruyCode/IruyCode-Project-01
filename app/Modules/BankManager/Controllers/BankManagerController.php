<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Modules\BankManager\Models\AccountBalance;



class BankManagerController extends Controller
{
    // public function index()
    // {
    //     // Carrega os dados principais do módulo
    //     $accounts = DB::table('app_bank_manager_account_balances')
    //         ->join('users', 'users.id', '=', 'app_bank_manager_account_balances.user_id')
    //         ->select(
    //             'app_bank_manager_account_balances.*',
    //             'users.name as user_name'
    //         )
    //         ->get();

    //     $transactions = DB::table('app_bank_manager_transactions')
    //         ->join('app_bank_manager_operation_categories', 'app_bank_manager_operation_categories.id', '=', 'app_bank_manager_transactions.operation_category_id')
    //         ->join('app_bank_manager_account_balances', 'app_bank_manager_account_balances.id', '=', 'app_bank_manager_transactions.account_balance_id')
    //         ->select(
    //             'app_bank_manager_transactions.*',
    //             'app_bank_manager_operation_categories.name as category_name',
    //             'app_bank_manager_account_balances.user_id'
    //         )
    //         ->orderByDesc('app_bank_manager_transactions.created_at')
    //         ->limit(10)
    //         ->get();

    //     // Retorna a view com os dados, ao usar o bankmanager::index e nao o bankmanager.index , ele busco a view no Modulo indicado e nao na pasta resources/views
    //     return view('bankmanager::app', [
    //         'accounts' => $accounts,
    //         'transactions' => $transactions,
    //     ]);
    // }

    public function accountBalances()
    {
        $user = Auth::user();
        $accounts = AccountBalance::where('user_id', $user->id)->get();

        return view('bankmanager::account-balances.index', compact('accounts'));
    }

    public function storeAccountBalance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'current_balance' => 'required|numeric|min:0',
            'account_type' => 'required|in:personal,business',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        AccountBalance::create([
            'user_id' => Auth::id(),
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'current_balance' => $request->current_balance,
            'account_type' => $request->account_type,
            'is_active' => true,
        ]);

        return redirect()->route('bank-manager.account-balances.index')->with('success', 'Conta bancária adicionada com sucesso!');
    }

    public function updateAccountBalance(Request $request, $id)
    {
        $account = AccountBalance::where('user_id', Auth::id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'current_balance' => 'required|numeric|min:0',
            'account_type' => 'required|in:personal,business',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $account->update([
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'current_balance' => $request->current_balance,
            'account_type' => $request->account_type,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('bank-manager.account-balances.index')->with('success', 'Conta bancária atualizada com sucesso!');
    }

    public function deleteAccountBalance($id)
    {
        $account = AccountBalance::where('user_id', Auth::id())->findOrFail($id);
        $account->delete();

        return redirect()->route('bank-manager.account-balances.index')->with('success', 'Conta bancária eliminada com sucesso!');
    }

    public function index()
    {
        $userId = Auth::id();

        // 1. Obter todas as contas ativas do utilizador
        $accounts = AccountBalance::where('user_id', $userId)
            ->where('is_active', true)
            ->get();

        // 2. Calcular saldos
        $totalBalance = $accounts->sum('current_balance');
        $personalBalance = $accounts->where('account_type', 'personal')->sum('current_balance');
        $businessBalance = $accounts->where('account_type', 'business')->sum('current_balance');

        // 3. Obter transações recentes (mantendo a lógica original, mas filtrando por user_id)
        $transactions = DB::table('app_bank_manager_transactions')
            ->join('app_bank_manager_operation_categories', 'app_bank_manager_operation_categories.id', '=', 'app_bank_manager_transactions.operation_category_id')
            ->join('app_bank_manager_account_balances', 'app_bank_manager_account_balances.id', '=', 'app_bank_manager_transactions.account_balance_id')
            ->select(
                'app_bank_manager_transactions.*',
                'app_bank_manager_operation_categories.name as category_name',
                'app_bank_manager_account_balances.user_id'
            )
            ->where('app_bank_manager_account_balances.user_id', $userId) // Filtrar por utilizador
            ->orderByDesc('app_bank_manager_transactions.created_at')
            ->limit(10)
            ->get();

        // Retorna a view com os dados
        return view('bankmanager::index', [
            'accounts' => $accounts,
            'transactions' => $transactions,
            'totalBalance' => $totalBalance,
            'personalBalance' => $personalBalance,
            'businessBalance' => $businessBalance,
        ]);
    }
}

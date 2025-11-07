<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankManagerController extends Controller
{
    public function index()
    {
        // Carrega os dados principais do módulo
        $accounts = DB::table('app_bank_manager_account_balances')
            ->join('users', 'users.id', '=', 'app_bank_manager_account_balances.user_id')
            ->select(
                'app_bank_manager_account_balances.*',
                'users.name as user_name'
            )
            ->get();

        $transactions = DB::table('app_bank_manager_transactions')
            ->join('app_bank_manager_operation_categories', 'app_bank_manager_operation_categories.id', '=', 'app_bank_manager_transactions.operation_category_id')
            ->join('app_bank_manager_account_balances', 'app_bank_manager_account_balances.id', '=', 'app_bank_manager_transactions.account_balance_id')
            ->select(
                'app_bank_manager_transactions.*',
                'app_bank_manager_operation_categories.name as category_name',
                'app_bank_manager_account_balances.user_id'
            )
            ->orderByDesc('app_bank_manager_transactions.created_at')
            ->limit(10)
            ->get();

        // Retorna a view com os dados, ao usar o bankmanager::index e nao o bankmanager.index , ele busco a view no Modulo indicado e nao na pasta resources/views
        return view('bankmanager::index', [
            'accounts' => $accounts,
            'transactions' => $transactions,
        ]);
    }
}

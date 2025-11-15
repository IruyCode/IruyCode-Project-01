<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\BankManager\Models\AccountBalance;
use App\Modules\BankManager\Models\OperationType;
use App\Modules\BankManager\Models\OperationCategory;
use App\Modules\BankManager\Models\OperationSubCategory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $operationTypes = OperationType::all();
        $operationCategories = OperationCategory::all();
        $operationSubCategories = OperationSubCategory::all();

        $operationCategoriesFilter = $operationCategories
            ->filter(fn($c) => !str_ends_with($c->name, '_Income') && !str_ends_with($c->name, '_Expenses'))
            ->values()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
            ]);

        $userId = Auth::id();

        // 1. Obter todas as contas ativas do utilizador
        $accounts = AccountBalance::where('user_id', $userId)
            ->where('is_active', true)
            ->get();

        // 2. Calcular saldos
        $totalBalance = $accounts->sum('current_balance');
        $personalBalance = $accounts->where('account_type', 'personal')->sum('current_balance');
        $businessBalance = $accounts->where('account_type', 'business')->sum('current_balance');

        // 3. Obter transações recentes (atualizado para refletir novas relações)
        $transactions = DB::table('app_bank_manager_transactions')
            ->join('app_bank_manager_operation_sub_categories', 'app_bank_manager_operation_sub_categories.id', '=', 'app_bank_manager_transactions.operation_sub_category_id')
            ->join('app_bank_manager_operation_types', 'app_bank_manager_operation_types.id', '=', 'app_bank_manager_transactions.operation_type_id')
            ->join('app_bank_manager_account_balances', 'app_bank_manager_account_balances.id', '=', 'app_bank_manager_transactions.account_balance_id')
            ->select(
                'app_bank_manager_transactions.*',
                'app_bank_manager_operation_types.operation_type as type_name',
                'app_bank_manager_operation_sub_categories.name as sub_category_name',
                'app_bank_manager_account_balances.user_id'
            )
            ->where('app_bank_manager_account_balances.user_id', $userId)
            ->orderByDesc('app_bank_manager_transactions.created_at')
            ->limit(10)
            ->get();

        // Retorna a view com os dados
        return view('bankmanager::dashboard.index', [
            'accounts' => $accounts,
            'transactions' => $transactions,
            'totalBalance' => $totalBalance,
            'personalBalance' => $personalBalance,
            'businessBalance' => $businessBalance,
            'operationTypes' => $operationTypes,
            'operationCategories' => $operationCategories,
            'operationSubCategories' => $operationSubCategories,
            'operationCategoriesFilter' => $operationCategoriesFilter,
        ]);
    }

    
}

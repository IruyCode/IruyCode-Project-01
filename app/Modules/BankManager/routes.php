<?php

use Illuminate\Support\Facades\Route;

use App\Modules\BankManager\Controllers\TransactionController;

use App\Modules\BankManager\Controllers\BankManagerController;
use App\Modules\BankManager\Controllers\DebtorsController;
use App\Modules\BankManager\Controllers\DebtsController;
use App\Modules\BankManager\Controllers\GoalController;
use App\Modules\BankManager\Controllers\InvestmentController;
use App\Modules\BankManager\Controllers\DashboardController;
use App\Modules\BankManager\Controllers\ApiBankManagerController;
use App\Modules\BankManager\Controllers\SettingsController;
use App\Modules\BankManager\Controllers\OperationSubCategoryController;
use App\Modules\BankManager\Controllers\OperationCategoryController;
use App\Modules\BankManager\Controllers\AccountBalanceController;


Route::prefix('bank-manager')
    ->name('bank-manager.')
    ->group(function () {

        // View Settings
        Route::get('/settings', [SettingsController::class, 'settings'])->name('settings');

        // View Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        // Transactions Routes
        Route::post('/transactions', [TransactionController::class, 'storeTransaction'])->name('transactions.store');

        // Operation Categories Routes
        Route::prefix('categories')
            ->name('categories.')
            ->controller(OperationCategoryController::class)
            ->group(function () {
                Route::get('/store', 'storeOperationCategory')->name('store');
                Route::get('/update/{id}', 'updateCategory')->name('update');
                Route::get('/delete/{id}', 'deleteCategory')->name('delete');
            });

        // Operation SubCategories Routes
        Route::prefix('subcategories')
            ->name('subcategories.')
            ->controller(OperationSubCategoryController::class)
            ->group(function () {
                Route::get('/store', 'storeOperationSubCategory')->name('store');
                Route::get('/update/{id}', 'updateSubCategory')->name('update');
                Route::get('/delete/{id}', 'deleteSubCategory')->name('delete');
            });

        // API Routes
        Route::prefix('api')
            ->name('api.')
            ->controller(InvestmentController::class)
            ->group(function () {
                Route::get('/receiveDataTableTransactions', [ApiBankManagerController::class, 'receiveAllTransactions']);
                Route::get('/subcategories/{category}', [ApiBankManagerController::class, 'getSubcategories']);
            });
        // Debtors Routes
        Route::prefix('debtors')
            ->name('debtors.')
            ->controller(DebtorsController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/storeDebtor', 'storeDebtor')->name('store');
                Route::post('/{debtor}/edit', 'editDebtor')->name('edit');
                Route::delete('/{debtor}', 'deleteDebtor')->name('destroy');
                Route::post('/{debtor}/conclude', 'concludeDebtor')->name('conclude');
                Route::post('/{debtor}/adjust-value', 'adjustValueDebtor')->name('adjust-value');
            });

        // Debts Routes
        Route::prefix('debts')
            ->name('debts.')
            ->controller(DebtsController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/storeDebt', 'storeDebt')->name('store');
                Route::delete('/{debt}/destroyDebt', 'deleteDebt')->name('destroy');
                Route::post('/{debt}/editDebt', 'editDebt')->name('edit');
                Route::post('/{installmentId}/installments/mark-paid', 'markInstallmentAsPaid')->name('installments.markPaid');

                Route::put('/{debt}/pay-multiples', 'payMultipleInstallments')->name('debts.pay-multiples');

                Route::post('/{debt}/finish', 'finishDebt')->name('finish');
                Route::put('/{debt}/adjust', 'adjustDebtValue')->name('adjust');
            });

        // Goals Routes
        Route::prefix('goals')
            ->name('goals.')
            ->controller(GoalController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'storeFinancialGoal')->name('store');
                Route::put('/{goal}', 'updateGoal')->name('update');
                Route::delete('/{goal}', 'destroyGoal')->name('destroy');
                Route::post('/{goal}/finish', 'finishGoal')->name('finish');
                Route::put('/{goal}/adjust', 'adjustGoalValue')->name('adjust');
            });

        // Investments Routes
        Route::prefix('investments')
            ->name('investments.')
            ->controller(InvestmentController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/storeInvestment', 'storeInvestment')->name('store');
                Route::post('/{investment}/edit', 'editInvestment')->name('edit');
                Route::delete('/{investment}', 'deleteInvestment')->name('destroy');

                Route::post('/{investment}/apply-cashflow', 'applyCashflow')->name('applyCashflow');
                Route::post('/{investment}/apply-market-update', 'applyMarketUpdate')->name('applyMarketUpdate');
            });

        // Account Balances Routes
        Route::prefix('account-balances')
            ->name('account-balances.')
            ->controller(AccountBalanceController::class)
            ->group(function () {
                Route::get('/', 'accountBalances')->name('index');
                Route::post('/', 'storeAccountBalance')->name('store');
                Route::put('/{id}', 'updateAccountBalance')->name('update');
                Route::delete('/{id}', 'deleteAccountBalance')->name('delete');
            });
    });

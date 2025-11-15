<?php

use Illuminate\Support\Facades\Route;

use App\Modules\BankManager\Controllers\TransactionController;

use App\Modules\BankManager\Controllers\BankManagerController;
use App\Modules\BankManager\Controllers\DebtorsController;
use App\Modules\BankManager\Controllers\DebtsController;
use App\Modules\BankManager\Controllers\GoalController;
use App\Modules\BankManager\Controllers\InvestmentController;




Route::prefix('bank-manager')
    ->name('bank-manager.')
    ->group(function () {

        Route::get('/api/receiveDataTableTransactions', [BankManagerController::class, 'receiveAllTransactions']);
        
        Route::get('/', [BankManagerController::class, 'index'])->name('index');

        Route::post('/operation-categories', [BankManagerController::class, 'storeOperationCategory'])->name('operation-categories.store');
        Route::post('/bank-manager/transactions', [BankManagerController::class, 'storeTransaction'])->name('transactions.store');


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
            ->controller(BankManagerController::class)
            ->group(function () {
                Route::get('/', 'accountBalances')->name('index');
                Route::post('/', 'storeAccountBalance')->name('store');
                Route::put('/{id}', 'updateAccountBalance')->name('update');
                Route::delete('/{id}', 'deleteAccountBalance')->name('delete');
            });
    });

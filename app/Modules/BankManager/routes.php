<?php

use Illuminate\Support\Facades\Route;

use App\Modules\BankManager\Controllers\TransactionController;

use App\Modules\BankManager\Controllers\BankManagerController;
use App\Modules\BankManager\Controllers\DebtorsController;
use App\Modules\BankManager\Controllers\DebtsController;
use App\Modules\BankManager\Controllers\GoalController;



Route::prefix('bank-manager')
    ->name('bank-manager.')
    ->group(function () {

        Route::get('/', [BankManagerController::class, 'index'])->name('index');

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
    });

<?php

use Illuminate\Support\Facades\Route;

use App\Modules\BankManager\Controllers\TransactionController;
use App\Modules\BankManager\Controllers\BankManagerController;
use App\Modules\BankManager\Controllers\DebtorsController;



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
            
    });

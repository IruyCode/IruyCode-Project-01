<?php

use Illuminate\Support\Facades\Route;
use App\Modules\BankManager\Controllers\TransactionController;
use App\Modules\BankManager\Controllers\BankManagerController;

Route::prefix('bank-manager')
    ->name('bank-manager.')
    ->group(function () {
        Route::get('/', [BankManagerController::class, 'index'])->name('index');
    });

<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Notifications\Controllers\NotificationController;

Route::prefix('core/notifications')
    ->name('core.notifications.')
    ->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/check', [NotificationController::class, 'markAsChecked'])->name('check');
        Route::post('/{id}/ignore', [NotificationController::class, 'markAsIgnored'])->name('ignore');
        Route::post('/check-all', [NotificationController::class, 'markAllAsChecked'])->name('check-all');
    });

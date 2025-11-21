<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckUserType;
use App\Http\Controllers\UserController;

require app_path('Modules/BankManager/routes.php');
require app_path('Modules/Notifications/routes.php');



Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::prefix('admin')
        ->name('admin.')
        ->middleware([CheckUserType::class])
        ->group(function () {
            Route::get('/', [UserController::class, 'dashboardAdmin'])->name('home');

            foreach (glob(base_path('app/Modules/*/routes.php')) as $routeFile) {
                require $routeFile;
            }
        });

    Route::prefix('client')
        ->name('client.')
        ->middleware([CheckUserType::class])
        ->group(function () {
            Route::get('/', [UserController::class, 'dashboardClient'])->name('dashboardClient');
        });
});

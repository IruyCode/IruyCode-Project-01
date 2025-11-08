<?php

use Illuminate\Support\Facades\Route;

require app_path('Modules/BankManager/routes.php');


foreach (glob(base_path('app/Modules/*/routes.php')) as $routeFile) {
    require $routeFile;
}


Route::get('/', function () {
    return view('welcome');
});


// Para testar as  paginas de erros
if (app()->environment('local')) {
    Route::get('/test-404', function () {
        return response()->view('errors.404', [], 404);
    });

    Route::get('/test-500', function () {
        return response()->view('errors.500', [], 500);
    });

    Route::get('/test-419', function () {
        return response()->view('errors.419', [], 419);
    });
}

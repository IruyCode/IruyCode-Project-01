<?php

use Illuminate\Support\Facades\Route;

foreach (glob(base_path('app/Modules/*/routes.php')) as $routeFile) {
    require $routeFile;
}


Route::get('/', function () {
    return view('welcome');
});

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function dashboardClient()
    {
        return view('dashboard-client');
    }

    public function dashboardAdmin()
    {
        return view('dashboard-admin');
    }
}

<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\BankManager\Models\OperationCategory;
use App\Modules\BankManager\Models\OperationSubCategory;

class SettingsController extends Controller
{
    public function settings()
    {
        $categories = OperationCategory::orderBy('name')->get();
        $subcategories = OperationSubCategory::with('operationCategory')
            ->orderBy('name')
            ->get();

        return view('bankmanager::settings', compact('categories', 'subcategories'));
    }
}

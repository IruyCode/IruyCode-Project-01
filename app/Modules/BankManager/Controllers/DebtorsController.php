<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\BankManager\Models\Debtors\Debtor;

use App\Modules\BankManager\Models\AccountBalance;

class DebtorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $debtors = Debtor::with('edits')->orderBy('created_at', 'desc')->get();
        $accountBalance = AccountBalance::firstOrCreate(['id' => 1], ['balance' => 0]);

        $totalAmountOwed = Debtor::sum('amount');
        $totalAmountPaid = Debtor::where('is_paid', true)->sum('amount');
        $totalPendingAmount = Debtor::where('is_paid', false)->sum('amount');

        return view('bankmanager::debtors/index', compact('debtors', 'accountBalance', 'totalAmountOwed', 'totalAmountPaid', 'totalPendingAmount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

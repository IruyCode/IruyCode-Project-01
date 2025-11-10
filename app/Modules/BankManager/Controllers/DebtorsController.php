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
    public function storeDebtor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
        ]);

        // Verifica se existe um devedor com o mesmo nome
        $exists = Debtor::where('name', $validated['name'])->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withErrors(['name' => 'Já existe um devedor com este nome.'])
                ->withInput();
        }

        Debtor::create($validated);

        return redirect()->back()->with('success', 'Devedor criado com sucesso.');
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

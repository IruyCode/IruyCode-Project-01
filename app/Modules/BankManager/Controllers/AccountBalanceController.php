<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Modules\BankManager\Models\AccountBalance;


class AccountBalanceController extends Controller
{
    public function accountBalances()
    {
        $user = Auth::user();
        $accounts = AccountBalance::where('user_id', $user->id)->get();

        return view('bankmanager::account-balances.index', compact('accounts'));
    }

    public function storeAccountBalance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'current_balance' => 'required|numeric|min:0',
            'account_type' => 'required|in:personal,business',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        AccountBalance::create([
            'user_id' => Auth::id(),
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'current_balance' => $request->current_balance,
            'account_type' => $request->account_type,
            'is_active' => true,
        ]);

        return redirect()->route('bank-manager.account-balances.index')->with('success', 'Conta bancária adicionada com sucesso!');
    }

    public function updateAccountBalance(Request $request, $id)
    {
        $account = AccountBalance::where('user_id', Auth::id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'current_balance' => 'required|numeric|min:0',
            'account_type' => 'required|in:personal,business',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $account->update([
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'current_balance' => $request->current_balance,
            'account_type' => $request->account_type,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('bank-manager.account-balances.index')->with('success', 'Conta bancária atualizada com sucesso!');
    }

    public function deleteAccountBalance($id)
    {
        $account = AccountBalance::where('user_id', Auth::id())->findOrFail($id);
        $account->delete();

        return redirect()->route('bank-manager.account-balances.index')->with('success', 'Conta bancária eliminada com sucesso!');
    }
}

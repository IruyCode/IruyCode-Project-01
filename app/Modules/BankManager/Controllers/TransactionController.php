<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\BankManager\Models\Transaction;
use App\Modules\BankManager\Models\AccountBalance;
use App\Modules\BankManager\Models\OperationType;

class TransactionController extends Controller
{
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'account_balance_id'        => 'required|exists:app_bank_manager_account_balances,id',
            'operation_sub_category_id' => 'required|exists:app_bank_manager_operation_sub_categories,id',
            'operation_type_id'         => 'required|exists:app_bank_manager_operation_types,id',
            'amount'                    => 'required|numeric|min:0.01',
        ]);

        $operationType = OperationType::findOrFail($request->operation_type_id);

        Transaction::create([
            'account_balance_id'        => $request->account_balance_id,
            'operation_sub_category_id' => $request->operation_sub_category_id,
            'operation_type_id'         => $operationType->id,
            'amount'                    => $request->amount,
        ]);

        $balance = AccountBalance::where('user_id', Auth::id())
            ->findOrFail($request->account_balance_id);

        if ($operationType->operation_type === 'income') {
            $balance->current_balance += $request->amount;
        } else {
            $balance->current_balance -= $request->amount;
        }

        $balance->save();

        return back()->with('success', 'Transação registrada com sucesso!');
    }
}

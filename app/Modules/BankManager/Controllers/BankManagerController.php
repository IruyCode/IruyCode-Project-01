<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Modules\BankManager\Models\AccountBalance;

use App\Modules\BankManager\Models\OperationCategory;
use App\Modules\BankManager\Models\OperationType;
use App\Modules\BankManager\Models\Transaction;
use App\Modules\BankManager\Models\TransactionDescription;
use App\Modules\BankManager\Models\FixedExpense\FixedExpense;
use App\Modules\BankManager\Models\OperationSubCategory;

use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;


class BankManagerController extends Controller
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

    public function storeOperationCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        OperationCategory::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Categoria criada com sucesso!');
    }

    public function storeOperationSubCategory(Request $request)
    {
        $request->validate([
            'operation_category_id' => 'required|exists:app_bank_manager_operation_categories,id',
            'name' => 'required|string|max:255',
        ]);

        OperationSubCategory::create([
            'operation_category_id' => $request->operation_category_id,
            'name' => $request->name,
        ]);

        return back()->with('success', 'Subcategoria criada com sucesso!');
    }

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

    //API to DataTables
    public function receiveAllTransactions(Request $request)
    {
        $tz = config('app.timezone', 'Europe/Lisbon');
        $now = Carbon::now($tz);

        $defaultMonth = $now->month;
        $defaultYear  = $now->year;

        // Agora carregamos TUDO o que precisamos
        $query = Transaction::with([
            'operationSubCategory.operationCategory',
            'accountBalance',
            'operationType'
        ]);

        $year  = (int) ($request->year  ?? $defaultYear);
        $month = (int) ($request->month ?? $defaultMonth);

        $query->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        // Filtro semana
        if ($request->filled('week')) {

            $startOfMonth = now()->setYear($year)->setMonth($month)->startOfMonth();
            $week = (int) $request->week;

            $weekStart = $startOfMonth->copy()->addDays(($week - 1) * 7);
            $weekEnd   = $weekStart->copy()->addDays(6)->endOfDay();

            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            if ($weekEnd->gt($endOfMonth)) $weekEnd = $endOfMonth;

            $query->whereBetween('created_at', [$weekStart, $weekEnd]);

            if ($request->filled('day')) {
                $query->whereDay('created_at', (int) $request->day);
            }
        }

        // Filtro tipo
        if ($request->filled('tipo')) {
            $query->whereHas('operationType', function ($q) use ($request) {
                if ($request->tipo === 'Receita') {
                    $q->where('operation_type', 'income');
                } elseif ($request->tipo === 'Despesa') {
                    $q->where('operation_type', 'expense');
                }
            });
        }

        // Filtro categoria (categoria pai)
        if ($request->filled('categoria')) {
            $query->whereHas('operationSubCategory', function ($q) use ($request) {
                $q->where('operation_category_id', $request->categoria);
            });
        }

        // Filtro subcategoria
        if ($request->filled('subcategoria')) {
            $query->where('operation_sub_category_id', $request->subcategoria);
        }


        return DataTables::eloquent($query)

            // SUBCATEGORIA
            ->addColumn(
                'subcategoria',
                fn($t) =>
                $t->operationSubCategory?->name ?? '—'
            )

            // CATEGORIA
            ->addColumn(
                'categoria',
                fn($t) =>
                $t->operationSubCategory?->operationCategory?->name ?? '—'
            )

            // BANCO
            ->addColumn(
                'bank_name',
                fn($t) =>
                $t->accountBalance?->bank_name ?? '—'
            )

            // TIPO DE CONTA
            ->addColumn(
                'account_type',
                fn($t) =>
                $t->accountBalance?->account_type ?? '—'
            )

            // VALOR
            ->addColumn('formatted_amount', function ($t) {

                $type = $t->operationType?->operation_type;

                $sign  = $type === 'income' ? '+ ' : '- ';
                $color = $type === 'income'
                    ? 'style="color: green; font-weight:bold;"'
                    : 'style="color: red; font-weight:bold;"';

                return "<span {$color}>{$sign}€ " .
                    number_format($t->amount, 2, ',', '.') .
                    '</span>';
            })

            // DATA
            ->addColumn(
                'formatted_date',
                fn($t) =>
                $t->created_at->format('d/m/Y')
            )

            // TIPO (Receita / Despesa)
            ->addColumn(
                'tipo',
                fn($t) =>
                $t->operationType?->operation_type === 'income'
                    ? 'Receita'
                    : 'Despesa'
            )

            ->rawColumns(['formatted_amount'])
            ->make(true);
    }



    public function settings()
    {
        $categories = OperationCategory::orderBy('name')->get();
        $subcategories = OperationSubCategory::with('operationCategory')
            ->orderBy('name')
            ->get();

        return view('bankmanager::settings', compact('categories', 'subcategories'));
    }

    //API to seetings - Get Subcategories by Category
    public function getSubcategories($categoryId)
    {
        $sub = OperationSubCategory::where('operation_category_id', $categoryId)
            ->orderBy('name')
            ->get();

        return response()->json($sub);
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = OperationCategory::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return back()->with('success', 'Categoria atualizada com sucesso!');
    }

    public function updateSubCategory(Request $request, $id)
    {
        $request->validate([
            'operation_category_id' => 'required|exists:app_bank_manager_operation_categories,id',
            'name' => 'required|string|max:255',
        ]);

        $sub = OperationSubCategory::findOrFail($id);
        $sub->operation_category_id = $request->operation_category_id;
        $sub->name = $request->name;
        $sub->save();

        return back()->with('success', 'Subcategoria atualizada com sucesso!');
    }

    public function deleteCategory($id)
    {
        $category = OperationCategory::findOrFail($id);

        // verificar se existe transação usando subcategorias desta categoria
        $hasTransactions = Transaction::whereHas('operationSubCategory', function ($q) use ($id) {
            $q->where('operation_category_id', $id);
        })->exists();

        if ($hasTransactions) {
            return back()->with('error', 'Não é possível apagar: existem transações usando esta categoria.');
        }

        // delete subcategorias
        OperationSubCategory::where('operation_category_id', $id)->delete();

        // delete categoria
        $category->delete();

        return back()->with('success', 'Categoria apagada com sucesso.');
    }


    public function deleteSubCategory($id)
    {
        $sub = OperationSubCategory::findOrFail($id);

        $hasTransactions = Transaction::where('operation_sub_category_id', $id)->exists();

        if ($hasTransactions) {
            return back()->with('error', 'Não é possível apagar: existem transações usando esta subcategoria.');
        }

        $sub->delete();

        return back()->with('success', 'Subcategoria apagada com sucesso.');
    }
}

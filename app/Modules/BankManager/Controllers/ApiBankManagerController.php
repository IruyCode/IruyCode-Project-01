<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\BankManager\Models\Transaction;
use App\Modules\BankManager\Models\OperationSubCategory;

use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ApiBankManagerController extends Controller
{
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
            // DESCRIÇÃO
            ->addColumn(
                'description',
                fn($t) =>
                $t->description ?? '—'
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

    //API to seetings - Get Subcategories by Category
    public function getSubcategories($categoryId)
    {
        $sub = OperationSubCategory::where('operation_category_id', $categoryId)
            ->orderBy('name')
            ->get();

        return response()->json($sub);
    }
}

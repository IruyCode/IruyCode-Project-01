<?php

namespace Database\Seeders\BankManager;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;


class BankManagerDebtorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        // 1. Inserir os tipos de operação
        $incomeId = DB::table('app_bank_manager_operation_types')->insertGetId([
            'operation_type' => 'income',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $expenseId = DB::table('app_bank_manager_operation_types')->insertGetId([
            'operation_type' => 'expense',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2. Inserir categorias vinculadas aos tipos
        $incomeCategories = [
            'Salário',
            'Renda extra',
            'Metas_Income',
            'Dívidas_Income',
            'Devedores_Income',
            'Investimentos_Income',
        ];

        $expenseCategories = [
            'Metas_Expenses',
            'Dívidas_Expenses',
            'Devedores_Expenses',
            'Investimentos_Expenses',
        ];


        foreach ($incomeCategories as $name) {
            DB::table('app_bank_manager_operation_categories')->updateOrInsert(
                ['name' => $name, 'operation_type_id' => $incomeId],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }

        foreach ($expenseCategories as $name) {
            DB::table('app_bank_manager_operation_categories')->updateOrInsert(
                ['name' => $name, 'operation_type_id' => $expenseId],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}

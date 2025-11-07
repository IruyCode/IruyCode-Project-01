<?php

namespace Database\Seeders\BankManager;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;


class BankManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        /* ==============================
         *  Tipos de operação
         * ============================== */
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

        /* ==============================
         * Categorias
         * ============================== */
        $incomeCategories = [
            'Salário',
            'Renda extra',
            'Metas_Income',
            'Dívidas_Income',
            'Devedores_Income',
            'Investimentos_Income'
        ];

        $expenseCategories = [
            'Metas_Expenses',
            'Dívidas_Expenses',
            'Devedores_Expenses',
            'Investimentos_Expenses'
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

        /* ==============================
         *  Contas por usuário
         * ============================== */
        $users = DB::table('users')->pluck('id');

        foreach ($users as $userId) {
            $accounts = [
                ['user_id' => $userId, 'balance' => rand(500, 5000), 'created_at' => $now, 'updated_at' => $now],
                ['user_id' => $userId, 'balance' => rand(1000, 8000), 'created_at' => $now, 'updated_at' => $now],
            ];
            DB::table('app_bank_manager_account_balances')->insert($accounts);
        }

        /* ==============================
         *  Transações (vinculadas às contas e categorias)
         * ============================== */
        $accounts = DB::table('app_bank_manager_account_balances')->get();
        $categories = DB::table('app_bank_manager_operation_categories')->get();

        foreach ($accounts as $account) {
            // cria entre 5 e 10 transações por conta
            $numTransactions = rand(5, 10);
            for ($i = 0; $i < $numTransactions; $i++) {
                $category = $categories->random();
                $amount = rand(50, 1000);
                $isIncome = DB::table('app_bank_manager_operation_types')
                    ->where('id', $category->operation_type_id)
                    ->value('operation_type') === 'income';

                DB::table('app_bank_manager_transactions')->insert([
                    'account_balance_id' => $account->id,
                    'operation_category_id' => $category->id,
                    'amount' => $amount,
                    'created_at' => $now->copy()->subDays(rand(0, 30)),
                    'updated_at' => $now,
                ]);

                // atualiza saldo da conta
                $account->balance += $isIncome ? $amount : -$amount;
            }

            DB::table('app_bank_manager_account_balances')
                ->where('id', $account->id)
                ->update(['balance' => $account->balance, 'updated_at' => $now]);
        }
    }
}

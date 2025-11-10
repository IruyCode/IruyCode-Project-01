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
         *  Devedores principais
         * ============================== */
        $debtors = [
            [
                'name' => 'João Pereira',
                'description' => 'Empréstimo pessoal para compra de computador.',
                'amount' => 1200.50,
                'due_date' => Carbon::now()->addDays(10),
                'is_paid' => false,
                'paid_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Maria Silva',
                'description' => 'Adiantamento de projeto de design.',
                'amount' => 800.00,
                'due_date' => Carbon::now()->subDays(5),
                'is_paid' => false,
                'paid_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Carlos Mendes',
                'description' => 'Dívida antiga quitada parcialmente.',
                'amount' => 500.00,
                'due_date' => Carbon::now()->subDays(15),
                'is_paid' => true,
                'paid_at' => Carbon::now()->subDays(10),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Empresa XPTO Ltda.',
                'description' => 'Serviço de consultoria ainda não pago.',
                'amount' => 2300.00,
                'due_date' => Carbon::now()->addDays(20),
                'is_paid' => false,
                'paid_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('app_bank_manager_debtors')->insert($debtors);

        /* ==============================
         *  Edições de valores e datas
         * ============================== */
        $debtorsInserted = DB::table('app_bank_manager_debtors')->get();

        foreach ($debtorsInserted as $debtor) {
            // Apenas alguns devedores terão histórico de edição
            if (rand(0, 1) === 1 && !$debtor->is_paid) {
                $oldAmount = $debtor->amount;
                $newAmount = $oldAmount + rand(-100, 300); // pode subir ou descer um pouco
                $oldDue = Carbon::parse($debtor->due_date);
                $newDue = $oldDue->copy()->addDays(rand(2, 15));

                DB::table('app_bank_manager_debtor_edits')->insert([
                    'debtor_id' => $debtor->id,
                    'old_amount' => $oldAmount,
                    'new_amount' => $newAmount,
                    'old_due_date' => $oldDue,
                    'new_due_date' => $newDue,
                    'reason' => 'Ajuste de valor e prazo para novo acordo.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Atualiza o devedor principal
                DB::table('app_bank_manager_debtors')
                    ->where('id', $debtor->id)
                    ->update([
                        'amount' => $newAmount,
                        'due_date' => $newDue,
                        'updated_at' => $now,
                    ]);
            }
        }
    }
}

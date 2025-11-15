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

        /* ============================================================
         | 1) TIPOS DE OPERAÇÃO (income / expense)
         ============================================================ */
        $operationTypes = [
            'income',
            'expense',
        ];

        $operationTypeIds = [];
        foreach ($operationTypes as $type) {
            $operationTypeIds[$type] = DB::table('app_bank_manager_operation_types')
                ->insertGetId([
                    'operation_type' => $type,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
        }

        /* ============================================================
         | 2) CATEGORIAS PRINCIPAIS (macro)
         ============================================================ */
        $categories = [
            'Alimentação'        => 'expense',
            'Lazer'              => 'expense',
            'Saúde'              => 'expense',
            'Casa'               => 'expense',
            'Transporte'         => 'expense',
            'Assinaturas'        => 'expense',
            'Investimentos'      => 'expense', // compra
            'Metas'              => 'expense', // aporte

            'Receitas Gerais'    => 'income',
            'Investimentos (Retorno)' => 'income', // rendimento
            'Metas (Saques)'          => 'income', // sacar meta
        ];

        $categoryIds = [];
        foreach ($categories as $catName => $typeName) {
            $categoryIds[$catName] = DB::table('app_bank_manager_operation_categories')
                ->insertGetId([
                    'name' => $catName,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
        }

        /* ============================================================
         | 3) SUBCATEGORIAS (micro)
         ============================================================ */

        $subCategories = [

            // Alimentação
            'Alimentação' => [
                'Supermercado',
                'Restaurante',
                'Café',
                'Delivery',
            ],

            // Lazer
            'Lazer' => [
                'Cinema',
                'Eventos',
                'Jogos',
                'Bar / Noite',
                'Viagens',
            ],

            // Saúde
            'Saúde' => [
                'Farmácia',
                'Consultas',
                'Exames',
                'Academia',
            ],

            // Casa
            'Casa' => [
                'Aluguel',
                'Conta de Luz',
                'Água',
                'Internet',
                'Manutenção',
            ],

            // Transporte
            'Transporte' => [
                'Uber',
                'Combustível',
                'Metro/Autocarro',
                'Estacionamento',
            ],

            // Assinaturas
            'Assinaturas' => [
                'Netflix',
                'Spotify',
                'Amazon Prime',
                'Apple One',
                'Outras assinaturas',
            ],

            // Investimentos (Entrada/Saída de valores)
            'Investimentos' => [
                'Ações',
                'ETFs',
                'Cripto',
                'Renda Fixa',
            ],

            // Metas (Guardar ou Sacar)
            'Metas' => [
                'Fundo Emergência',
                'Casa Própria',
                'Aposentadoria',
                'Viagem',
                'Estudos',
            ],

            // Receitas Gerais
            'Receitas Gerais' => [
                'Salário',
                'Renda Extra',
                'Prémios',
                'Reembolso',
            ],

            // Investimentos (Retorno)
            'Investimentos (Retorno)' => [
                'Dividendos',
                'Juros',
                'Rentabilidade',
            ],

            // Metas (Saques)
            'Metas (Saques)' => [
                'Saque Meta – Emergência',
                'Saque Meta – Objetivo',
            ],
        ];

        foreach ($subCategories as $categoryName => $subs) {
            foreach ($subs as $subName) {
                DB::table('app_bank_manager_operation_sub_categories')->insert([
                    'operation_category_id' => $categoryIds[$categoryName],
                    'name' => $subName,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}

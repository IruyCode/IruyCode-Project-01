<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabela para histórico mensal (para comparação)
        Schema::create('app_bank_manager_investment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_id') // Corrigido: removido o $ extra
                ->constrained('app_bank_manager_investments')
                ->cascadeOnDelete();

            // Dados do registro histórico
            $table->date('reference_date'); // Data de referência (último dia do mês)
            $table->decimal('amount', 15, 2); // Valor naquela data
            $table->decimal('variation', 10, 2)->nullable(); // Variação em relação ao mês anterior
            $table->decimal('percentage', 5, 2)->nullable(); // Porcentagem de variação

            $table->timestamps();

            // Garante que não haverá duplicatas
            $table->unique(['investment_id', 'reference_date'], 'investment_ref_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bank_manager_investment_history');
    }
};

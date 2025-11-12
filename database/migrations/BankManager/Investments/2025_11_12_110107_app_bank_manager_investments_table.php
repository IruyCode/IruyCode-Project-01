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
        Schema::create('app_bank_manager_investments', function (Blueprint $table) {
            $table->id();

            // Dados básicos do investimento
            $table->string('name'); // Nome do investimento (ex: "Tesouro Selic 2025")
            $table->string('platform'); // Plataforma (ex: "XP Investimentos", "Rico")
            $table->string('type')->nullable(); // Tipo (ex: "Renda Fixa", "Ações", "FII")

            // Valores e histórico
            $table->decimal('initial_amount', 15, 2); // Valor inicial investido
            $table->decimal('current_amount', 15, 2); // Valor atual
            $table->date('start_date'); // Data de início
            $table->decimal('monthly_profit', 10, 2)->nullable(); // Lucro mensal estimado

            // Controle e status
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable(); // Observações adicionais

            // Timestamps padrão
            $table->timestamps();
            $table->softDeletes(); // Para deletar de forma reversível
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bank_manager_investments');
    }
};

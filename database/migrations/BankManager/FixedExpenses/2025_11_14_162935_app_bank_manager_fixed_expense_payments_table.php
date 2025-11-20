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
        Schema::create('app_bank_manager_fixed_expense_payments', function (Blueprint $table) {
            $table->id();
            // Liga com a despesa fixa
            $table->foreignId('fixed_expense_id')->constrained('app_bank_manager_fixed_expenses')->onDelete('cascade');
            // Data real do pagamento
            $table->date('paid_at');
            // Controle de ano e mês (para não duplicar)
            $table->year('year');
            $table->unsignedTinyInteger('month'); // 1-12
            $table->timestamps();
            // Garante que uma despesa só pode ter 1 pagamento por mês
            $table->unique(['fixed_expense_id', 'year', 'month'], 'fx_expense_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bank_manager_fixed_expense_payments');
    }
};

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
        Schema::create('app_bank_manager_debt_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debt_id')
                ->constrained('app_bank_manager_debts')
                ->onDelete('cascade');
            $table->unsignedInteger('installment_number');
            $table->decimal('amount', 10, 2);
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bank_manager_debt_installments');
    }
};

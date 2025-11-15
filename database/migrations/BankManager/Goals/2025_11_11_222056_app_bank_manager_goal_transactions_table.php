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
        Schema::create('app_bank_manager_goal_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained('app_bank_manager_financial_goals')->onDelete('cascade');
            $table->enum('type', ['aporte', 'retirada']); // entrada ou saída da meta
            $table->decimal('amount', 12, 2);
            $table->text('note')->nullable(); // motivo ou observação
            $table->timestamp('performed_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bank_manager_goal_transactions');
    }
};

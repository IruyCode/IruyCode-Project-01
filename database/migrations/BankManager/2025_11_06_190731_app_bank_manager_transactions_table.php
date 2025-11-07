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
        Schema::create('app_bank_manager_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_balance_id')->constrained('app_bank_manager_account_balances')->onDelete('cascade');
            $table->foreignId('operation_category_id')->constrained('app_bank_manager_operation_categories')->onDelete('cascade');
            $table->decimal('amount', 10, 2); // até 99999999.99
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bank_manager_transactions');
    }
};

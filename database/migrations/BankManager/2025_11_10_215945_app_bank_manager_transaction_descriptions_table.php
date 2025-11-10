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
        Schema::create('app_bank_manager_transaction_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('app_bank_manager_transactions')->onDelete('cascade');

            $table->string('description'); // Ex: "Tesouro Selic (Investimentos_Expenses)"

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bank_manager_transaction_descriptions');
    }
};

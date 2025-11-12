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
        Schema::create('app_bank_manager_investment_history_transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('investment_id');
            $table->enum('type', ['aporte', 'retirada']);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->timestamp('performed_at')->useCurrent();

            $table->timestamps();

            // Foreign key com nome mais curto
            $table->foreign('investment_id', 'inv_hist_trans_fk')
                ->references('id')
                ->on('app_bank_manager_investments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bank_manager_investment_history_transactions');
    }
};

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
        Schema::create('app_bank_manager_fixed_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->unsignedTinyInteger('due_day');
            $table->unsignedBigInteger('operation_sub_category_id');
            $table->date('last_generated_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('operation_sub_category_id', 'fx_expenses_subcat_fk')
                ->references('id')
                ->on('app_bank_manager_operation_sub_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bank_manager_fixed_expenses');
    }
};

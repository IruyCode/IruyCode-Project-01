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
        Schema::create('app_bank_manager_operation_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_category_id')
                ->constrained('app_bank_manager_operation_categories', 'id', indexName: 'op_subcat_cat_fk')
                ->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bank_manager_operation_sub_categories');
    }
};

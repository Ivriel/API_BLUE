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
        Schema::create('store_balance_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('store_balance_id');
            $table->foreign('store_balance_id')->references('id')->on('store_balances')->cascadeOnDelete();
            $table->enum('type', ['income', 'withdraw']);
            $table->uuid('reference_id');
            $table->string('reference_type');
            $table->decimal('amount', 26, 2);
            $table->string('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_balance_histories');
    }
};

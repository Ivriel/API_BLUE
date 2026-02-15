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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete();
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->integer('qty');
            $table->decimal('subtotal', 26, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};

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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->uuid('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('buyers')->cascadeOnDelete();
            $table->uuid('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
            $table->text('address');
            $table->string('address_id');
            $table->string('city');
            $table->string('postal_code');
            $table->string('shipping');
            $table->string('shipping_type');
            $table->decimal('shipping_cost', 26, 2);
            $table->string('tracking_number')->nullable();
            $table->decimal('tax', 26, 2);
            $table->decimal('grand_total', 26, 2);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

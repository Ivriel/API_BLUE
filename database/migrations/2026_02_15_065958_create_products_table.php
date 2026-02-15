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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
            $table->uuid('product_category_id');
            $table->foreign('product_category_id')->references('id')->on('product_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->enum('condition', ['new', 'second']);
            $table->decimal('price', 26, 2);
            $table->integer('weight');
            $table->integer('stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Note: The original orders table was missing the id column and had a typo (recept_path).
     * Since SQLite doesn't support adding a primary key to an existing table,
     * we recreate the orders table with the correct structure.
     */
    public function up(): void
    {
        // Drop and recreate order_items first (has FK to orders)
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');

        // Recreate orders table with proper structure
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('payment_method')->default('cash');
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('receipt_path')->nullable();
            $table->timestamps();
        });

        // Recreate order_items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop and recreate with original (buggy) structure
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');

        Schema::create('orders', function (Blueprint $table) {
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('recept_path')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }
};

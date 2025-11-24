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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->index();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('price')->nullable();
            $table->enum('discount_type', ['amount', 'percentage'])->default('amount');
            $table->integer('discount_amount')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->boolean('need_preparation_time')->default(false);
            $table->integer('preparation_time')->nullable();
            $table->boolean('has_order_limit')->default(false);
            $table->integer('order_limit')->nullable();
            $table->boolean('is_default')->default(false)->index();
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};

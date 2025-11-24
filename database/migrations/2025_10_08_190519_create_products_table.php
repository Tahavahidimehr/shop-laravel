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
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->index();
            $table->enum('type', ['simple', 'variable'])->default('simple')->index();
            $table->integer('price')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->enum('discount_type', ['amount', 'percentage'])->default('amount');
            $table->integer('discount_amount')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->boolean('special_offer')->default(false)->index();
            $table->boolean('need_preparation_time')->default(false);
            $table->integer('preparation_time')->nullable();
            $table->enum('weight_unit', ['kg', 'g'])->default('g');
            $table->float('weight');
            $table->longText('description')->nullable();
            $table->boolean('has_order_limit')->default(false);
            $table->integer('order_limit')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('sales_count')->default(0);
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft')->index();
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

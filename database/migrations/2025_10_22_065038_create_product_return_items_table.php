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
        Schema::create('product_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_return_id')->constrained('product_returns')->cascadeOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained('order_items')->nullOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->morphs('returnable');
            $table->integer('quantity')->default(1);
            $table->integer('cost_price')->default(0);
            $table->integer('refund_amount')->default(0);
            $table->integer('profit_loss')->default(0);
            $table->enum('condition', ['new', 'used', 'damaged'])->default('new');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_return_items');
    }
};

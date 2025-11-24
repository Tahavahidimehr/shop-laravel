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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique()->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discount_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipping_method_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->text('address');
            $table->string('postal_code');
            $table->string('province');
            $table->string('city');
            $table->string('payment_method');
            $table->string('shipping_method');
            $table->string('discount_code')->nullable();
            $table->enum('status', [
                'pending',
                'paid',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
                'failed',
            ])->default('pending')->index();
            $table->integer('cost_of_goods')->default(0);
            $table->integer('profit')->default(0);
            $table->integer('total_price')->default(0);
            $table->integer('discount_code_price')->default(0);
            $table->integer('total_product_discount_price')->default(0);
            $table->integer('tax_price')->default(0);
            $table->integer('shipping_price')->default(0);
            $table->integer('packing_price')->default(0);
            $table->integer('shipping_cost_real')->default(0);
            $table->integer('packing_cost_real')->default(0);
            $table->integer('price_to_pay')->default(0);
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('reservation_released_at')->nullable()->index();
            $table->string('tracking_code')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

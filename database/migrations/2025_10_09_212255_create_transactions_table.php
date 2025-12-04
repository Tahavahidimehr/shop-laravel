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
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
                $table->string('payment_method_name');
                $table->string('track_id')->nullable()->index();
                $table->string('reference_id')->nullable()->index();
                $table->string('order_code')->nullable()->index();
                $table->string('error_message')->nullable();
                $table->enum('status', [
                    'pending',
                    'success',
                    'failed',
                    'canceled'
                ])->default('pending')->index();
                $table->integer('price_to_pay');
                $table->integer('gateway_fee')->default(0);
                $table->string('card_number')->nullable();
                $table->json('verify_response')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });

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

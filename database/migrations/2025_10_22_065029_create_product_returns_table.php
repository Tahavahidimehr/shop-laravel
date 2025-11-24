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
        Schema::create('product_returns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->string('reason')->nullable();
            $table->integer('total_refund_amount')->default(0);
            $table->integer('total_cost')->default(0);
            $table->integer('total_profit_loss')->default(0);
            $table->text('admin_note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_returns');
    }
};

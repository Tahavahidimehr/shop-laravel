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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['amount', 'percentage'])->default('amount');
            $table->integer('amount')->nullable();
            $table->integer('percentage')->nullable();
            $table->integer('max_discount_price')->nullable();
            $table->integer('min_purchase_price')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->integer('per_user_limit')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};

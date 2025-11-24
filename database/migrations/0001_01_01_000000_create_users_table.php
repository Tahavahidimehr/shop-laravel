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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile')->unique();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('sheba_number')->nullable();
            $table->string('card_number')->nullable();
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->timestamp('last_order_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

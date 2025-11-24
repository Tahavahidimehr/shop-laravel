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
        Schema::create('variant_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained()->cascadeOnDelete();
            $table->string('value')->index();
            $table->timestamps();
        });

        Schema::create('product_variant_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_value_id')->constrained()->cascadeOnDelete();
            $table->unique(['product_variant_id','variant_value_id'], 'product_variant_values_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_values');
        Schema::dropIfExists('variant_values');
    }
};

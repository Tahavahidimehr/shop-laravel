<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_values', 'variant_value_id', 'product_variant_id');
    }
}

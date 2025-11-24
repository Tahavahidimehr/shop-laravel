<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'integer',
        'discount_amount' => 'integer',
        'discount_percentage' => 'integer',
        'need_preparation_time' => 'boolean',
        'preparation_time' => 'integer',
        'has_order_limit' => 'boolean',
        'order_limit' => 'integer',
        'is_default' => 'boolean',
        'discount_type' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($variant) {
            if ($variant->is_default) {
                static::where('product_id', $variant->product_id)
                    ->where('id', '!=', $variant->id)
                    ->update(['is_default' => false]);
            }
        });
    }

    protected $appends = [
        'total_stock',
        'is_available',
        'original_price',
        'final_price',
        'discount_percent_for_badge',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function inventoryStocks()
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function variantValues()
    {
        return $this->belongsToMany(
            VariantValue::class,
            'product_variant_values',
            'product_variant_id',
            'variant_value_id'
        )->with('variant');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /*
    |--------------------------------------------------------------------------
    | Inventory Accessors
    |--------------------------------------------------------------------------
    */

    public function getTotalStockAttribute(): int
    {
        return (int) $this->inventoryStocks()->sum('quantity');
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'active' && $this->total_stock > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Pricing Accessors
    |--------------------------------------------------------------------------
    */

    public function getOriginalPriceAttribute(): ?int
    {
        return $this->price !== null ? (int) $this->price : null;
    }

    public function getFinalPriceAttribute(): ?int
    {
        if ($this->price === null) return null;

        $price = (int) $this->price;

        if ($this->discount_type === 'percentage' && $this->discount_percentage > 0) {
            return max(0, (int) round($price * (1 - $this->discount_percentage / 100)));
        }

        if ($this->discount_type === 'amount' && $this->discount_amount > 0) {
            return max(0, $price - (int) $this->discount_amount);
        }

        return $price;
    }

    public function getDiscountPercentForBadgeAttribute(): ?int
    {
        $orig = $this->original_price;
        $final = $this->final_price;

        if ($orig === null || $final === null || $final >= $orig) {
            return null;
        }

        return (int) round(($orig - $final) / $orig * 100);
    }
}

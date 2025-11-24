<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'integer',
        'discount_amount' => 'integer',
        'discount_percentage' => 'integer',
        'special_offer' => 'boolean',
        'need_preparation_time' => 'boolean',
        'preparation_time' => 'integer',
        'weight' => 'integer',
        'has_order_limit' => 'boolean',
        'order_limit' => 'integer',
        'views_count' => 'integer',
        'sales_count' => 'integer',
        'status' => 'string',
        'discount_type' => 'string',
    ];

    protected $appends = [
        'total_stock',
        'is_available',
        'original_price',
        'final_price',
        'discount_percent_for_badge',
        'sort_price',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')->where(function ($q) {

            // SIMPLE
            $q->where(function ($q2) {
                $q2->where('type', 'simple')->whereHas('inventoryStocks', function ($x) {
                    $x->where('quantity', '>', 0);
                });
            })

                // VARIABLE
                ->orWhere(function ($q2) {
                    $q2->where('type', 'variable')->whereHas('variants', function ($v) {
                        $v->where('status', 'active')
                            ->whereHas('inventoryStocks', function ($x) {
                                $x->where('quantity', '>', 0);
                            });
                    });
                });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function inventoryStocks()
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)
            ->with(['variantValues.variant', 'media']);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /*
    |--------------------------------------------------------------------------
    | Inventory
    |--------------------------------------------------------------------------
    */

    public function getTotalStockAttribute(): int
    {
        if ($this->type === 'simple') {
            return (int) $this->inventoryStocks()->sum('quantity');
        }

        // variable → مجموع موجودی تمام واریانت‌ها
        return (int) InventoryStock::query()
            ->where('product_id', $this->id)
            ->sum('quantity');
    }

    public function getIsAvailableAttribute(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        return $this->total_stock > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Pricing Helpers
    |--------------------------------------------------------------------------
    */

    protected function basePrice(): ?int
    {
        return $this->type === 'simple' && $this->price !== null
            ? (int) $this->price
            : null;
    }

    public function pricingVariant(): ?ProductVariant
    {
        if ($this->type !== 'variable') {
            return null;
        }

        // اگر واریانت‌ها قبلاً لود شده باشند از همان استفاده کن
        $variants = $this->relationLoaded('variants')
            ? $this->variants
            : $this->variants()->with('inventoryStocks')->get();

        // فقط واریانت‌های موجود و فعال
        $available = $variants->filter(function (ProductVariant $v) {
            return $v->is_available;
        });

        if ($available->isEmpty()) {
            return null;
        }

        // همیشه ارزان‌ترین واریانت بر اساس final_price انتخاب شود
        return $available
            ->sortBy(function (ProductVariant $v) {
                return $v->final_price ?? PHP_INT_MAX;
            })
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Pricing Accessors
    |--------------------------------------------------------------------------
    */

    public function getOriginalPriceAttribute(): ?int
    {
        if ($this->type === 'simple') {
            return $this->basePrice();
        }

        return $this->pricingVariant()?->original_price;
    }

    public function getFinalPriceAttribute(): ?int
    {
        if ($this->type === 'simple') {

            $price = $this->basePrice();
            if ($price === null) return null;

            if ($this->discount_type === 'percentage' && $this->discount_percentage > 0) {
                return max(0, (int) round($price * (1 - $this->discount_percentage / 100)));
            }

            if ($this->discount_type === 'amount' && $this->discount_amount > 0) {
                return max(0, $price - (int) $this->discount_amount);
            }

            return $price;
        }

        return $this->pricingVariant()?->final_price;
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

    public function getSortPriceAttribute(): ?int
    {
        return $this->final_price ?? $this->original_price;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'integer',
        'percentage' => 'integer',
        'max_discount_price' => 'integer',
        'min_purchase_price' => 'integer',
        'is_active' => 'boolean',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'per_user_limit' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function userUsages()
    {
        return $this->hasMany(DiscountUserUsage::class);
    }
}

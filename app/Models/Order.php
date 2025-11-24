<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'total_price' => 'integer',
        'discount_code_price' => 'integer',
        'total_product_discount_price' => 'integer',
        'tax_price' => 'integer',
        'shipping_price' => 'integer',
        'packing_price' => 'integer',
        'price_to_pay' => 'integer',
        'expires_at' => 'datetime',
        'shipped_at' => 'datetime',
        'paid_at' => 'datetime',
        'status' => 'string',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

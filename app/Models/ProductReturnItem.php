<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturnItem extends Model
{
    /** @use HasFactory<\Database\Factories\ProductReturnItemFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'refund_amount' => 'integer',
        'cost_price' => 'integer',
        'profit_loss' => 'integer',
    ];

    public function productReturn()
    {
        return $this->belongsTo(ProductReturn::class);
    }

    public function returnable()
    {
        return $this->morphTo();
    }
}

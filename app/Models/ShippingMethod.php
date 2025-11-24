<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    /** @use HasFactory<\Database\Factories\ShippingMethodFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
        'config' => 'encrypted:array',
    ];
}

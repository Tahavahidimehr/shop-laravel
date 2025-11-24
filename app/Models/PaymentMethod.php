<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentMethodFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'extra_fee' => 'integer',
        'config' => 'encrypted:array'
    ];
}

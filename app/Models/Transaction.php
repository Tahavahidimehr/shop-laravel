<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'order_id' => 'integer',
        'user_id' => 'integer',
        'payment_method_id' => 'integer',
        'price_to_pay' => 'integer',
        'verify_response' => 'array',
        'paid_at' => 'datetime',
        'status' => 'string',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountUserUsage extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountUserUsageFactory> */
    use HasFactory;

    protected $casts = [
        'used_count' => 'integer',
    ];
}

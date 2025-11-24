<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_open' => 'boolean',
        'automatic_order_confirmation' => 'boolean',
        'tax_percentage' => 'integer',
        'temp_reserve_time' => 'integer',
    ];
}

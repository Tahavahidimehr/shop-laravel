<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyCode extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime'
    ];
}

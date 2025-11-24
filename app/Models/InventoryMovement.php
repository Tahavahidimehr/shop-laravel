<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryMovementFactory> */
    use HasFactory;

    protected $guarded = [];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function movable()
    {
        return $this->morphTo();
    }
}

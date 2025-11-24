<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    /** @use HasFactory<\Database\Factories\WarehouseFactory> */
    use HasFactory;

    protected $guarded = [];

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryStock extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryStockFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'integer',
        'average_cost' => 'integer',
        'warehouse_id' => 'integer',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function stockable(): MorphTo
    {
        return $this->morphTo();
    }

    public function updateStock(int $movementQuantity, ?int $movementCost = null, string $type = 'in'): void
    {
        if ($type === 'in') {
            $totalQuantity = $this->quantity + $movementQuantity;

            $newAverageCost = $totalQuantity > 0
                ? intdiv(($this->quantity * $this->average_cost) + ($movementQuantity * ($movementCost ?? 0)), $totalQuantity)
                : 0;

            $this->update([
                'quantity' => $totalQuantity,
                'average_cost' => $newAverageCost,
            ]);
        } elseif ($type === 'out') {
            $this->update([
                'quantity' => max($this->quantity - $movementQuantity, 0),
            ]);
        }
    }
}

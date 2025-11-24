<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\InventoryStock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReleaseExpiredOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // فقط سفارش‌هایی که:
        // status = pending
        // expires_at < now
        // reserve آزاد نشده باشد (reservation_released_at null)

        $orders = Order::query()
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->whereNull('reservation_released_at')
            ->with('items')
            ->get();

        foreach ($orders as $order) {

            DB::beginTransaction();

            try {
                foreach ($order->items as $item) {

                    $stockQuery = InventoryStock::query()
                        ->orderBy('id'); // مهم نیست، فقط یکی را می‌گیریم

                    if ($item->product_variant_id) {
                        $stockQuery->where('product_variant_id', $item->product_variant_id);
                    } else {
                        $stockQuery->where('product_id', $item->product_id);
                    }

                    $stock = $stockQuery->first();

                    if ($stock) {
                        $stock->update([
                            'quantity' => $stock->quantity + $item->quantity
                        ]);
                    }
                }

                // علامت‌گذاری که آزاد شده
                $order->update([
                    'reservation_released_at' => now(),
                ]);

                DB::commit();

                Log::info("Released stock for expired order #{$order->id}");

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("ReleaseExpiredOrders failed for order #{$order->id}: " . $e->getMessage());
            }
        }
    }
}

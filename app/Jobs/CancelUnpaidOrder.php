<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelUnpaidOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $orderId;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
        $this->onQueue('orders');
    }

    public function handle(): void
    {
        // سفارش را پیدا کن
        $order = Order::with('items')->find($this->orderId);

        if (!$order) {
            Log::warning("CancelUnpaidOrder: Order not found #{$this->orderId}");
            return;
        }

        // اگر سفارش "pending" نیست → یعنی یا پرداخت شده، یا کنسل شده، یا failed شده
        // پس هیچ کاری نباید انجام شود
        if ($order->status !== 'pending') {
            Log::info("CancelUnpaidOrder: Order #{$order->id} already has final status: {$order->status}");
            return;
        }

        // اگر هنوز زمان انقضای پرداخت نرسیده (expires_at > now)
        if ($order->expires_at && now()->lessThan($order->expires_at)) {
            Log::info("CancelUnpaidOrder: Order #{$order->id} still within payment window, skipping.");
            return;
        }

        // ❗❗ مهم‌ترین بخش:
        // در این لحظه سفارش *منقضی شده* اما نباید:
        // - لغو شود
        // - موجودی آزاد شود
        // چون ممکن است کاربر الان داخل درگاه مشغول پرداخت باشد.

        Log::info("CancelUnpaidOrder: Order #{$order->id} payment window expired but still pending. Waiting for payment callback.");
    }
}

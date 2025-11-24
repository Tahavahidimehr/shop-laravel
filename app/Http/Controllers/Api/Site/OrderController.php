<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Jobs\CancelUnpaidOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingMethod;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    use ApiResponse;

    public function store(Request $request): JsonResponse
    {
        $validator = validator($request->all(), [
            'address_id' => 'required|exists:addresses,id',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        try {

            return DB::transaction(function () use ($request) {

                $address = auth()->user()->addresses()->findOrFail($request->address_id);
                $shipping = ShippingMethod::findOrFail($request->shipping_method_id);

                $cartItems = [];
                $total_price = 0;
                $total_product_discount = 0;

                foreach ($request->items as $item) {

                    $product = Product::findOrFail($item['product_id']);
                    $variant = $item['variant_id']
                        ? ProductVariant::where('product_id', $product->id)->findOrFail($item['variant_id'])
                        : null;

                    // موجودی
                    $stock = $variant
                        ? $variant->inventoryStocks()->sum('quantity')
                        : $product->inventoryStocks()->sum('quantity');

                    if ($stock < $item['quantity']) {
                        return $this->errorResponse(
                            "موجودی کافی برای محصول {$product->name} وجود ندارد.",
                            null,
                            422
                        );
                    }

                    // محدودیت سفارش
                    $limitSource = $variant ?? $product;
                    if ($limitSource->has_order_limit && $limitSource->order_limit < $item['quantity']) {
                        return $this->errorResponse(
                            "حداکثر تعداد قابل سفارش برای {$product->name}، {$limitSource->order_limit} است.",
                            null,
                            422
                        );
                    }

                    // قیمت واقعی
                    $unit_original = $variant ? $variant->original_price : $product->original_price;
                    $unit_final = $variant ? $variant->final_price : $product->final_price;

                    $line_total = $unit_final * $item['quantity'];
                    $line_discount = ($unit_original - $unit_final) * $item['quantity'];

                    $total_price += $line_total;
                    $total_product_discount += $line_discount;

                    $cartItems[] = [
                        'product' => $product,
                        'variant' => $variant,
                        'quantity' => $item['quantity'],
                        'unit_price' => $unit_final,
                        'discount_price' => $unit_original - $unit_final,
                        'line_total' => $line_total,
                    ];
                }

                // ایجاد سفارش
                $order = Order::create([
                    'order_code' => Str::upper(Str::random(10)),
                    'user_id' => auth()->id(),

                    'address_id' => $address->id,
                    'shipping_method_id' => $shipping->id,
                    'payment_method_id' => null,

                    'name' => $address->receiver_name,
                    'phone' => $address->receiver_phone,
                    'address' => $address->address,
                    'postal_code' => $address->postal_code,
                    'province' => $address->province,
                    'city' => $address->city,

                    'payment_method' => 'online',
                    'shipping_method' => $shipping->name,

                    'total_price' => $total_price,
                    'total_product_discount_price' => $total_product_discount,
                    'shipping_price' => $shipping->price,
                    'price_to_pay' => $total_price + $shipping->price,

                    // ⏳ رزرو ۱۵ دقیقه
                    'expires_at' => now()->addMinutes(15),
                ]);

                // ذخیره آیتم‌ها + کاهش موجودی
                foreach ($cartItems as $c) {

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $c['product']->id,
                        'product_variant_id' => $c['variant']->id ?? null,
                        'quantity' => $c['quantity'],
                        'unit_price' => $c['unit_price'],
                        'discount_price' => $c['discount_price'],
                        'price_to_pay' => $c['line_total'],
                        'cost_price' => 0,
                        'profit' => 0,
                    ]);

                    $stock = $c['variant']
                        ? $c['variant']->inventoryStocks()->first()
                        : $c['product']->inventoryStocks()->first();

                    if ($stock) {
                        $stock->updateStock($c['quantity'], null, 'out');
                    }
                }

                // 📌 Job برای لغو اتوماتیک
                CancelUnpaidOrder::dispatch($order)->delay(now()->addMinutes(15));

                return $this->successResponse(
                    $order->load('items'),
                    "سفارش با موفقیت ایجاد شد",
                    201
                );
            });

        } catch (\Exception $e) {
            Log::error("Order create failed: " . $e->getMessage());
            return $this->errorResponse(
                "خطا در ایجاد سفارش",
                $e->getMessage(),
                500
            );
        }
    }
}

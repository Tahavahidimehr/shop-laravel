<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use ApiResponse;

    public function sync(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "items" => "required|array",
            "items.*.product_id" => "required|integer|exists:products,id",
            "items.*.variant_id" => "nullable|integer|exists:product_variants,id",
            "items.*.quantity" => "required|integer|min:1",

            "items.*.original_price" => "required|integer|min:0",
            "items.*.unit_price" => "required|integer|min:0",

            "items.*.discount_type" => "nullable|string",
            "items.*.discount_value" => "nullable|integer|min:0",
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $items = $validator->validated()["items"];
        $result = [];

        foreach ($items as $item) {

            $product = Product::with(["media"])->find($item["product_id"]);
            $variant = $item["variant_id"]
                ? ProductVariant::with(["media", "variantValues.variant"])->find($item["variant_id"])
                : null;

            // ❌ ناموجود
            if (!$product || !$product->is_available) {
                $result[] = $this->makeDeleted($item, "این محصول دیگر موجود نیست");
                continue;
            }

            if ($product->type === "variable" && (!$variant || !$variant->is_available)) {
                $result[] = $this->makeDeleted($item, "این واریانت دیگر موجود نیست");
                continue;
            }

            $changes = [];

            // قیمت‌ها
            $src = $variant ?? $product;

            $currentOriginal = $src->price;
            $currentDiscountType = $src->discount_type;

            $currentDiscountValue = null;
            if ($currentDiscountType === "amount") {
                $currentDiscountValue = $src->discount_amount;
            }
            if ($currentDiscountType === "percentage") {
                $currentDiscountValue = $src->discount_percentage;
            }

            $currentFinal = $src->final_price;

            // بررسی تغییر قیمت
            if ($currentOriginal != $item["original_price"]) {
                $changes[] = ($currentOriginal > $item["original_price"])
                    ? "قیمت اصلی افزایش یافته است"
                    : "قیمت اصلی کاهش یافته است";
            }

            if ($currentFinal != $item["unit_price"]) {
                $changes[] = ($currentFinal > $item["unit_price"])
                    ? "قیمت قابل پرداخت افزایش یافته"
                    : "قیمت قابل پرداخت کاهش یافته";
            }

            // تغییر تخفیف
            if ($item["discount_type"] != $currentDiscountType) {
                $changes[] = $currentDiscountType
                    ? "تخفیف جدید فعال شده است"
                    : "تخفیف این محصول حذف شده است";
            }

            // کنترل موجودی
            $stock = $variant ? $variant->total_stock : $product->total_stock;

            if ($stock <= 0) {
                $result[] = $this->makeDeleted($item, "موجودی محصول تمام شده است");
                continue;
            }

            // order_limit
            $limit = $variant->order_limit ?? $product->order_limit;
            $maxQty = $limit ? min($limit, $stock) : $stock;

            $finalQty = min($item["quantity"], $maxQty);

            if ($item["quantity"] > $maxQty) {
                $changes[] = "حداکثر تعداد مجاز {$maxQty} عدد است.";
            }

            // 🔥 خروجی کامل محصول + واریانت
            $result[] = [
                "product_id" => $product->id,
                "variant_id" => $variant->id ?? null,

                "quantity" => $finalQty,

                "original_price" => $currentOriginal,
                "unit_price" => $currentFinal,

                "discount_type" => $currentDiscountType,
                "discount_value" => $currentDiscountValue,

                "product" => $product,
                "variant" => $variant,

                "changes" => $changes,
            ];
        }

        return $this->successResponse([
            "items" => $result,
        ], "Cart synced successfully");
    }

    private function makeDeleted($item, $msg)
    {
        return [
            "product_id" => $item["product_id"],
            "variant_id" => $item["variant_id"] ?? null,
            "quantity" => 0,
            "original_price" => $item["original_price"],
            "unit_price" => $item["unit_price"],
            "discount_type" => null,
            "discount_value" => null,

            "product" => null,
            "variant" => null,

            "changes" => [$msg],
        ];
    }
}

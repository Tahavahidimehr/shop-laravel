<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductVariantRequest;
use App\Http\Requests\Admin\UpdateProductVariantRequest;
use App\Models\ProductVariant;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductVariantController extends Controller
{
    use ApiResponse;

    /**
     * لیست واریانت‌ها
     */
    public function index(): JsonResponse
    {
        try {
            $variants = ProductVariant::with(['product', 'variantValues.variant', 'media'])
                ->latest()
                ->paginate(20);

            return $this->successResponse($variants, 'لیست واریانت‌های محصولات با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error fetching product variants: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست واریانت‌های محصولات');
        }
    }

    /**
     * ایجاد واریانت جدید
     */
    public function store(StoreProductVariantRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $variant = DB::transaction(function () use ($data) {
                // حذف فیلدهایی که در جدول وجود ندارند
                $cleanData = collect($data)->except(['variant_values', 'media_ids'])->toArray();

                // ساخت SKU مشابه ProductController
                $sku = $this->generateUniqueSku('variant');

                // ایجاد واریانت
                $variant = ProductVariant::create(array_merge($cleanData, [
                    'sku' => $sku,
                    'status' => $data['status'] ?? 'draft',
                ]));

                // سینک روابط Pivot
                if (!empty($data['variant_values'])) {
                    $variant->variantValues()->sync($data['variant_values']);
                }

                if (!empty($data['media_ids'])) {
                    $variant->media()->sync($data['media_ids']);
                }

                return $variant;
            });

            $variant->load(['product', 'variantValues.variant', 'media']);

            return $this->successResponse($variant, 'واریانت محصول با موفقیت ایجاد شد', 201);

        } catch (\Throwable $e) {
            Log::error('Error creating product variant: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد واریانت محصول');
        }
    }

    /**
     * نمایش جزئیات واریانت
     */
    public function show(ProductVariant $productVariant): JsonResponse
    {
        try {
            $productVariant->load(['product', 'variantValues.variant', 'media']);
            return $this->successResponse($productVariant, 'جزئیات واریانت محصول با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error showing product variant: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت جزئیات واریانت محصول');
        }
    }

    /**
     * بروزرسانی واریانت
     */
    public function update(UpdateProductVariantRequest $request, ProductVariant $productVariant): JsonResponse
    {
        try {
            $data = $request->validated();

            // حذف فیلدهایی که در جدول نیستند
            $cleanData = collect($data)->except(['sku', 'variant_values', 'media_ids'])->toArray();

            $productVariant->update($cleanData);

            // بروزرسانی روابط
            if (isset($data['variant_values'])) {
                $productVariant->variantValues()->sync($data['variant_values']);
            }

            if (isset($data['media_ids'])) {
                $productVariant->media()->sync($data['media_ids']);
            }

            $productVariant->load(['product', 'variantValues.variant', 'media']);

            return $this->successResponse($productVariant, 'واریانت محصول با موفقیت بروزرسانی شد');

        } catch (\Throwable $e) {
            Log::error('Error updating product variant: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی واریانت محصول');
        }
    }

    /**
     * حذف واریانت
     */
    public function destroy(ProductVariant $productVariant): JsonResponse
    {
        try {
            DB::transaction(function () use ($productVariant) {
                $productVariant->media()->detach();
                $productVariant->delete();
            });

            return $this->successResponse(null, 'واریانت محصول با موفقیت حذف شد');
        } catch (\Throwable $e) {
            Log::error('Error deleting product variant: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف واریانت محصول');
        }
    }

    /**
     * تولید SKU منحصربه‌فرد برای واریانت‌ها
     */
    private function generateUniqueSku(string $type = 'variant'): string
    {
        $prefix = $type === 'variant' ? 'VAR' : 'PRD';
        $datePart = now()->format('ymd');
        $baseSku = "{$prefix}-{$datePart}";
        $sku = $baseSku;

        $counter = 1;
        while (ProductVariant::where('sku', $sku)->exists()) {
            $sku = "{$baseSku}-{$counter}";
            $counter++;
        }

        return $sku;
    }
}

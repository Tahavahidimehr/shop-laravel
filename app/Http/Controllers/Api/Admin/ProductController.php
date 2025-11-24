<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $products = Product::with([
                'media',
                'mainImage',
                'category',
                'variants.media',
                'variants.variantValues.variant',
                'attributes'
            ])
                ->latest()
                ->get();

            return $this->successResponse($products, 'لیست محصولات با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error fetching admin product list: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست محصولات');
        }
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $product = DB::transaction(function () use ($data) {
                $slugBase = Str::slug($data['name']);
                $slug = $this->generateUniqueSlug($slugBase);

                $skuType = $data['type'] === 'variable' ? 'variant' : 'product';
                $sku = $this->generateUniqueSku($skuType);

                return Product::create(array_merge($data, [
                    'slug' => $slug,
                    'sku' => $sku,
                    'status' => 'draft'
                ]));
            });

            return $this->successResponse($product, 'محصول با موفقیت ایجاد شد', 201);

        } catch (\Throwable $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد محصول');
        }
    }

    public function show(Product $product): JsonResponse
    {
        try {
            $product->load([
                'media',
                'mainImage',
                'category',
                'variants.media',
                'variants.variantValues.variant',
                'attributes'
            ]);

            return $this->successResponse($product, 'جزئیات محصول با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error showing product: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت جزئیات محصول');
        }
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        try {
            $data = $request->validated();
            $product->update(collect($data)->except(['slug', 'sku'])->toArray());

            return $this->successResponse($product, 'محصول با موفقیت بروزرسانی شد');
        } catch (\Throwable $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی محصول');
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            DB::transaction(function () use ($product) {
                $product->delete();
            });

            return $this->successResponse(null, 'محصول با موفقیت حذف شد');
        } catch (\Throwable $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف محصول');
        }
    }

    private function generateUniqueSlug(string $base): string
    {
        $slug = $base;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function generateUniqueSku(string $type = 'product'): string
    {
        $prefix = $type === 'variant' ? 'VAR' : 'PRD';
        $datePart = now()->format('ymd');
        $baseSku = "{$prefix}-{$datePart}";
        $sku = $baseSku;

        $counter = 1;
        while (Product::where('sku', $sku)->exists()) {
            $sku = "{$baseSku}-{$counter}";
            $counter++;
        }

        return $sku;
    }
}

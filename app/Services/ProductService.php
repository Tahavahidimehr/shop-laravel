<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * ایجاد محصول با تمام جزئیات (مدیا، ویژگی‌ها و تنوع‌ها)
     */
    public function createProduct(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $slugBase = Str::slug($data['name']);
            $slug = Product::where('slug', $slugBase)->exists()
                ? $slugBase . '-' . rand(100, 999)
                : $slugBase;

            $product = Product::create([
                'name' => $data['name'],
                'slug' => $slug,
                'price' => $data['price'] ?? null,
                'category_id' => $data['category_id'] ?? null,
                'discount_type' => $data['discount_type'] ?? 'amount',
                'discount_amount' => $data['discount_amount'] ?? null,
                'discount_percentage' => $data['discount_percentage'] ?? null,
                'special_offer' => $data['special_offer'] ?? false,
                'description' => $data['description'] ?? null,
                'weight' => $data['weight'] ?? 0,
                'weight_unit' => $data['weight_unit'] ?? 'g',
                'need_preparation_time' => $data['need_preparation_time'] ?? false,
                'preparation_time' => $data['preparation_time'] ?? null,
                'has_order_limit' => $data['has_order_limit'] ?? false,
                'order_limit' => $data['order_limit'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            $this->syncMedia($product, $data['media'] ?? []);
            $this->syncAttributes($product, $data['attributes'] ?? []);
            $this->syncVariants($product, $data['variants'] ?? []);

            return $product->load(['media', 'variants.media', 'variants.attributes', 'attributes']);
        });
    }

    /**
     * بروزرسانی محصول با تمام جزئیات
     */
    public function updateProduct(int $id, array $data): Product
    {
        return DB::transaction(function () use ($id, $data) {
            $product = Product::with(['media', 'variants.media', 'variants.attributes'])->findOrFail($id);

            $product->update([
                'name' => $data['name'] ?? $product->name,
                'price' => $data['price'] ?? $product->price,
                'category_id' => $data['category_id'] ?? $product->category_id,
                'description' => $data['description'] ?? $product->description,
                'is_active' => $data['is_active'] ?? $product->is_active,
                'discount_type' => $data['discount_type'] ?? $product->discount_type,
                'discount_amount' => $data['discount_amount'] ?? $product->discount_amount,
                'discount_percentage' => $data['discount_percentage'] ?? $product->discount_percentage,
                'special_offer' => $data['special_offer'] ?? $product->special_offer,
                'weight' => $data['weight'] ?? $product->weight,
                'weight_unit' => $data['weight_unit'] ?? $product->weight_unit,
                'need_preparation_time' => $data['need_preparation_time'] ?? $product->need_preparation_time,
                'preparation_time' => $data['preparation_time'] ?? $product->preparation_time,
                'has_order_limit' => $data['has_order_limit'] ?? $product->has_order_limit,
                'order_limit' => $data['order_limit'] ?? $product->order_limit,
            ]);

            $this->syncMedia($product, $data['media'] ?? []);
            $this->syncAttributes($product, $data['attributes'] ?? []);
            $this->syncVariants($product, $data['variants'] ?? []);

            return $product->load(['media', 'variants.media', 'variants.attributes', 'attributes']);
        });
    }

    /**
     * حذف محصول و همه وابستگی‌ها
     */
    public function deleteProduct(int $id): void
    {
        DB::transaction(function () use ($id) {
            $product = Product::with(['media', 'variants.media', 'variants.attributes'])->findOrFail($id);

            // حذف مدیا محصول و فایل‌ها
            foreach ($product->media as $media) {
                Storage::delete($media->path);
                $media->delete();
            }

            // حذف تنوع‌ها و مدیا و ویژگی‌ها
            foreach ($product->variants as $variant) {
                foreach ($variant->media as $vMedia) {
                    Storage::delete($vMedia->path);
                    $vMedia->delete();
                }
                $variant->attributes()->detach();
                $variant->delete();
            }

            // حذف ویژگی‌ها
            $product->attributes()->detach();

            // حذف خود محصول
            $product->delete();
        });
    }

    /**
     * متد کمکی: sync مدیا
     */
    private function syncMedia($model, array $mediaData): void
    {
        $existingIds = collect($mediaData)->pluck('id')->filter()->toArray();

        foreach ($model->media as $media) {
            if (!in_array($media->id, $existingIds)) {
                Storage::delete($media->path);
                $media->delete();
            }
        }

        foreach ($mediaData as $media) {
            if (isset($media['id'])) {
                $m = $model->media()->find($media['id']);
                if ($m) {
                    $m->update($media);
                }
            } else {
                $model->media()->create($media);
            }
        }
    }

    /**
     * متد کمکی: sync ویژگی‌ها
     */
    private function syncAttributes($product, array $attributes): void
    {
        $syncData = [];
        foreach ($attributes as $attrId => $valueId) {
            $syncData[$attrId] = ['attribute_value_id' => $valueId];
        }
        $product->attributes()->sync($syncData);
    }

    /**
     * متد کمکی: sync تنوع‌ها
     */
    private function syncVariants($product, array $variants): void
    {
        $existingIds = collect($variants)->pluck('id')->filter()->toArray();

        foreach ($product->variants as $variant) {
            if (!in_array($variant->id, $existingIds)) {
                foreach ($variant->media as $vMedia) {
                    Storage::delete($vMedia->path);
                    $vMedia->delete();
                }
                $variant->delete();
            }
        }

        foreach ($variants as $variantData) {
            if (isset($variantData['id'])) {
                $variant = $product->variants()->find($variantData['id']);
                if ($variant) {
                    $variant->update($variantData);
                    $this->syncMedia($variant, $variantData['media'] ?? []);
                }
            } else {
                $variant = $product->variants()->create($variantData);
                $this->syncMedia($variant, $variantData['media'] ?? []);
            }
        }
    }
}

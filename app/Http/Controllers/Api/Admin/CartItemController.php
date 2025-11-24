<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCartItemRequest;
use App\Http\Requests\Admin\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartItemController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $items = CartItem::with(['cart', 'product', 'productVariant'])->latest()->paginate(15);
            return $this->successResponse($items, 'لیست آیتم‌های سبد خرید با موفقیت دریافت شد.');
        } catch (\Throwable $e) {
            Log::error('Error fetching cart items: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست آیتم‌های سبد خرید');
        }
    }

    public function store(StoreCartItemRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $item = CartItem::create($request->validated());
            DB::commit();
            return $this->successResponse($item, 'آیتم با موفقیت به سبد خرید اضافه شد.', 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creating cart item: ' . $e->getMessage());
            return $this->errorResponse('خطا در افزودن آیتم به سبد خرید');
        }
    }

    public function show(CartItem $cartItem): JsonResponse
    {
        try {
            $cartItem->load(['cart', 'product', 'productVariant']);
            return $this->successResponse($cartItem, 'جزئیات آیتم سبد خرید با موفقیت دریافت شد.');
        } catch (\Throwable $e) {
            Log::error('Error showing cart item: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت جزئیات آیتم سبد خرید');
        }
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): JsonResponse
    {
        DB::beginTransaction();
        try {
            $cartItem->update($request->validated());
            DB::commit();
            return $this->successResponse($cartItem, 'آیتم سبد خرید با موفقیت بروزرسانی شد.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error updating cart item: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی آیتم سبد خرید');
        }
    }

    public function destroy(CartItem $cartItem): JsonResponse
    {
        DB::beginTransaction();
        try {
            $cartItem->delete();
            DB::commit();
            return $this->successResponse(null, 'آیتم سبد خرید با موفقیت حذف شد.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error deleting cart item: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف آیتم سبد خرید');
        }
    }
}

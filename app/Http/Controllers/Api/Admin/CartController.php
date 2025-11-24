<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCartRequest;
use App\Http\Requests\Admin\UpdateCartRequest;
use App\Models\Cart;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $carts = Cart::with('user')->latest()->paginate(15);
            return $this->successResponse($carts, 'لیست سبدهای خرید با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error fetching carts: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست سبدها');
        }
    }

    public function store(StoreCartRequest $request): JsonResponse
    {
        try {
            $cart = Cart::create($request->validated());
            return $this->successResponse($cart, 'سبد خرید با موفقیت ایجاد شد', 201);
        } catch (\Throwable $e) {
            Log::error('Error creating cart: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد سبد خرید');
        }
    }

    public function show(Cart $cart): JsonResponse
    {
        try {
            $cart->load('user');
            return $this->successResponse($cart, 'جزئیات سبد خرید با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error showing cart: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت جزئیات سبد خرید');
        }
    }

    public function update(UpdateCartRequest $request, Cart $cart): JsonResponse
    {
        try {
            $cart->update($request->validated());
            return $this->successResponse($cart, 'سبد خرید با موفقیت بروزرسانی شد');
        } catch (\Throwable $e) {
            Log::error('Error updating cart: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی سبد خرید');
        }
    }

    public function destroy(Cart $cart): JsonResponse
    {
        try {
            $cart->delete();
            return $this->successResponse(null, 'سبد خرید با موفقیت حذف شد');
        } catch (\Throwable $e) {
            Log::error('Error deleting cart: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف سبد خرید');
        }
    }
}

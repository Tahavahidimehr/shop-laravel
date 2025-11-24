<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrderItemRequest;
use App\Http\Requests\Admin\UpdateOrderItemRequest;
use App\Models\OrderItem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderItemController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $items = OrderItem::latest()->paginate(10);
            return $this->successResponse($items, 'لیست آیتم‌های سفارش دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching order items: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست آیتم‌ها');
        }
    }

    public function store(StoreOrderItemRequest $request): JsonResponse
    {
        try {
            $item = OrderItem::create($request->validated());
            return $this->successResponse($item, 'آیتم سفارش با موفقیت ثبت شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating order item: ' . $e->getMessage());
            return $this->errorResponse('خطا در ثبت آیتم سفارش');
        }
    }

    public function show(OrderItem $orderItem): JsonResponse
    {
        try {
            return $this->successResponse($orderItem, 'آیتم سفارش دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing order item: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت آیتم سفارش');
        }
    }

    public function update(UpdateOrderItemRequest $request, OrderItem $orderItem): JsonResponse
    {
        try {
            $orderItem->update($request->validated());
            return $this->successResponse($orderItem, 'آیتم سفارش بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating order item: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی آیتم سفارش');
        }
    }

    public function destroy(OrderItem $orderItem): JsonResponse
    {
        try {
            $orderItem->delete();
            return $this->successResponse(null, 'آیتم سفارش حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting order item: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف آیتم سفارش');
        }
    }
}

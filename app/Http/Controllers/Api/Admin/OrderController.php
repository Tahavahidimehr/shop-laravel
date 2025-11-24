<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrderRequest;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $orders = Order::latest()->paginate(10);
            return $this->successResponse($orders, 'لیست سفارش‌ها با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست سفارش‌ها');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = Order::create($request->validated());
            return $this->successResponse($order, 'سفارش با موفقیت ثبت شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());
            return $this->errorResponse('خطا در ثبت سفارش');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        try {
            return $this->successResponse($order, 'سفارش با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing order: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت اطلاعات سفارش');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        try {
            $order->update($request->validated());
            return $this->successResponse($order, 'سفارش با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating order: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی سفارش');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): JsonResponse
    {
        try {
            $order->delete();
            return $this->successResponse(null, 'سفارش با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف سفارش');
        }
    }
}

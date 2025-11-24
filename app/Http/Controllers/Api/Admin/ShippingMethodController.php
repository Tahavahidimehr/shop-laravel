<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShippingMethodRequest;
use App\Http\Requests\Admin\UpdateShippingMethodRequest;
use App\Models\ShippingMethod;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ShippingMethodController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $methods = ShippingMethod::latest()->paginate(10);
            return $this->successResponse($methods, 'لیست روش‌های ارسال با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching shipping methods: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست روش‌های ارسال');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingMethodRequest $request): JsonResponse
    {
        try {
            $method = ShippingMethod::create($request->validated());
            return $this->successResponse($method, 'روش ارسال با موفقیت ایجاد شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating shipping method: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد روش ارسال');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingMethod $shippingMethod): JsonResponse
    {
        try {
            return $this->successResponse($shippingMethod, 'روش ارسال با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing shipping method: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت اطلاعات روش ارسال');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingMethodRequest $request, ShippingMethod $shippingMethod): JsonResponse
    {
        try {
            $shippingMethod->update($request->validated());
            return $this->successResponse($shippingMethod, 'روش ارسال با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating shipping method: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی روش ارسال');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingMethod $shippingMethod): JsonResponse
    {
        try {
            $shippingMethod->delete();
            return $this->successResponse(null, 'روش ارسال با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting shipping method: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف روش ارسال');
        }
    }
}

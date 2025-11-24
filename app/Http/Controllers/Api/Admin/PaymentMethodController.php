<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentMethodRequest;
use App\Http\Requests\Admin\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PaymentMethodController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $methods = PaymentMethod::latest()->paginate(10);
            return $this->successResponse($methods, 'لیست روش‌های پرداخت با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching payment methods: '.$e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست روش‌های پرداخت');
        }
    }

    public function store(StorePaymentMethodRequest $request): JsonResponse
    {
        try {
            $method = PaymentMethod::create($request->validated());
            return $this->successResponse($method, 'روش پرداخت با موفقیت ایجاد شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating payment method: '.$e->getMessage());
            return $this->errorResponse('خطا در ایجاد روش پرداخت');
        }
    }

    public function show(PaymentMethod $paymentMethod): JsonResponse
    {
        try {
            return $this->successResponse($paymentMethod, 'روش پرداخت با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing payment method: '.$e->getMessage());
            return $this->errorResponse('خطا در دریافت اطلاعات روش پرداخت');
        }
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod): JsonResponse
    {
        try {
            $paymentMethod->update($request->validated());
            return $this->successResponse($paymentMethod, 'روش پرداخت با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating payment method: '.$e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی روش پرداخت');
        }
    }

    public function destroy(PaymentMethod $paymentMethod): JsonResponse
    {
        try {
            $paymentMethod->delete();
            return $this->successResponse(null, 'روش پرداخت با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting payment method: '.$e->getMessage());
            return $this->errorResponse('خطا در حذف روش پرداخت');
        }
    }
}

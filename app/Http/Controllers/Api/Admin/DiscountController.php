<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDiscountRequest;
use App\Http\Requests\Admin\UpdateDiscountRequest;
use App\Models\Discount;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $discounts = Discount::latest()->paginate(10);

            return $this->successResponse($discounts, 'لیست تخفیف‌ها با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching discounts: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست تخفیف‌ها');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiscountRequest $request): JsonResponse
    {
        try {
            $discount = Discount::create($request->validated());

            return $this->successResponse($discount, 'تخفیف با موفقیت ایجاد شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating discount: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد تخفیف');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount): JsonResponse
    {
        try {
            return $this->successResponse($discount, 'تخفیف با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing discount: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت اطلاعات تخفیف');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountRequest $request, Discount $discount): JsonResponse
    {
        try {
            $discount->update($request->validated());

            return $this->successResponse($discount, 'تخفیف با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating discount: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی تخفیف');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount): JsonResponse
    {
        try {
            $discount->delete();

            return $this->successResponse(null, 'تخفیف با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting discount: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف تخفیف');
        }
    }
}

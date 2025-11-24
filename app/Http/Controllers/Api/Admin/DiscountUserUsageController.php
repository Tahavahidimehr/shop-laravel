<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDiscountUserUsageRequest;
use App\Http\Requests\Admin\UpdateDiscountUserUsageRequest;
use App\Models\DiscountUserUsage;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DiscountUserUsageController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $usages = DiscountUserUsage::with(['discount', 'user'])->latest()->paginate(15);
            return $this->successResponse($usages, 'لیست استفاده کاربران از تخفیف‌ها با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching discount user usages: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست استفاده کاربران از تخفیف‌ها');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiscountUserUsageRequest $request): JsonResponse
    {
        try {
            $usage = DiscountUserUsage::create($request->validated());
            return $this->successResponse($usage, 'استفاده کاربر از تخفیف با موفقیت ثبت شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating discount user usage: ' . $e->getMessage());
            return $this->errorResponse('خطا در ثبت استفاده کاربر از تخفیف');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DiscountUserUsage $discountUserUsage): JsonResponse
    {
        try {
            $discountUserUsage->load(['discount', 'user']);
            return $this->successResponse($discountUserUsage, 'اطلاعات استفاده کاربر از تخفیف با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing discount user usage: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت اطلاعات استفاده کاربر از تخفیف');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountUserUsageRequest $request, DiscountUserUsage $discountUserUsage): JsonResponse
    {
        try {
            $discountUserUsage->update($request->validated());
            return $this->successResponse($discountUserUsage, 'استفاده کاربر از تخفیف با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating discount user usage: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی استفاده کاربر از تخفیف');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DiscountUserUsage $discountUserUsage): JsonResponse
    {
        try {
            $discountUserUsage->delete();
            return $this->successResponse(null, 'استفاده کاربر از تخفیف با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting discount user usage: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف استفاده کاربر از تخفیف');
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVariantValueRequest;
use App\Http\Requests\Admin\UpdateVariantValueRequest;
use App\Models\VariantValue;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class VariantValueController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $values = VariantValue::with('variant')->latest()->get();
            return $this->successResponse($values, 'لیست مقادیر واریانت با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error fetching variant values: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست مقادیر واریانت');
        }
    }

    public function store(StoreVariantValueRequest $request): JsonResponse
    {
        try {
            $variantValue = VariantValue::create($request->validated());
            return $this->successResponse($variantValue, 'مقدار واریانت با موفقیت ایجاد شد', 201);
        } catch (\Throwable $e) {
            Log::error('Error creating variant value: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد مقدار واریانت');
        }
    }

    public function show(VariantValue $variantValue): JsonResponse
    {
        try {
            $variantValue->load('variant');
            return $this->successResponse($variantValue, 'جزئیات مقدار واریانت با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error showing variant value: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت جزئیات مقدار واریانت');
        }
    }

    public function update(UpdateVariantValueRequest $request, VariantValue $variantValue): JsonResponse
    {
        try {
            $variantValue->update($request->validated());
            return $this->successResponse($variantValue, 'مقدار واریانت با موفقیت بروزرسانی شد');
        } catch (\Throwable $e) {
            Log::error('Error updating variant value: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی مقدار واریانت');
        }
    }

    public function destroy(VariantValue $variantValue): JsonResponse
    {
        try {
            $variantValue->delete();
            return $this->successResponse(null, 'مقدار واریانت با موفقیت حذف شد');
        } catch (\Throwable $e) {
            Log::error('Error deleting variant value: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف مقدار واریانت');
        }
    }
}

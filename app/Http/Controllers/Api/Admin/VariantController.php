<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVariantRequest;
use App\Http\Requests\Admin\UpdateVariantRequest;
use App\Models\Variant;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class VariantController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $variants = Variant::with('values')->latest()->get();
            return $this->successResponse($variants, 'لیست واریانت‌ها با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error fetching variants: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست واریانت‌ها');
        }
    }

    public function store(StoreVariantRequest $request): JsonResponse
    {
        try {
            $variant = Variant::create($request->validated());
            return $this->successResponse($variant, 'واریانت با موفقیت ایجاد شد', 201);
        } catch (\Throwable $e) {
            Log::error('Error creating variant: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد واریانت');
        }
    }

    public function show(Variant $variant): JsonResponse
    {
        try {
            return $this->successResponse($variant, 'جزئیات واریانت با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error showing variant: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت جزئیات واریانت');
        }
    }

    public function update(UpdateVariantRequest $request, Variant $variant): JsonResponse
    {
        try {
            $variant->update($request->validated());
            return $this->successResponse($variant, 'واریانت با موفقیت بروزرسانی شد');
        } catch (\Throwable $e) {
            Log::error('Error updating variant: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی واریانت');
        }
    }

    public function destroy(Variant $variant): JsonResponse
    {
        try {
            $variant->delete();
            return $this->successResponse(null, 'واریانت با موفقیت حذف شد');
        } catch (\Throwable $e) {
            Log::error('Error deleting variant: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف واریانت');
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePackagingTypeRequest;
use App\Http\Requests\Admin\UpdatePackagingTypeRequest;
use App\Models\PackagingType;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PackagingTypeController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $types = PackagingType::latest()->paginate(10);
            return $this->successResponse($types, 'لیست نوع بسته‌بندی با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching packaging types: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست نوع بسته‌بندی');
        }
    }

    public function store(StorePackagingTypeRequest $request): JsonResponse
    {
        try {
            $type = PackagingType::create($request->validated());
            return $this->successResponse($type, 'نوع بسته‌بندی با موفقیت ایجاد شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating packaging type: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد نوع بسته‌بندی');
        }
    }

    public function show(PackagingType $packagingType): JsonResponse
    {
        try {
            return $this->successResponse($packagingType, 'نوع بسته‌بندی با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing packaging type: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت اطلاعات نوع بسته‌بندی');
        }
    }

    public function update(UpdatePackagingTypeRequest $request, PackagingType $packagingType): JsonResponse
    {
        try {
            $packagingType->update($request->validated());
            return $this->successResponse($packagingType, 'نوع بسته‌بندی با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating packaging type: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی نوع بسته‌بندی');
        }
    }

    public function destroy(PackagingType $packagingType): JsonResponse
    {
        try {
            $packagingType->delete();
            return $this->successResponse(null, 'نوع بسته‌بندی با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting packaging type: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف نوع بسته‌بندی');
        }
    }
}

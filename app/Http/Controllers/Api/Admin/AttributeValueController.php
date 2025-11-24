<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAttributeValueRequest;
use App\Http\Requests\Admin\UpdateAttributeValueRequest;
use App\Models\AttributeValue;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AttributeValueController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $values = AttributeValue::with('attribute')->latest()->paginate(15);
            return $this->successResponse($values,'لیست مقادیر ویژگی‌ها با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error fetching attribute values: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت مقادیر ویژگی‌ها');
        }
    }

    public function store(StoreAttributeValueRequest $request): JsonResponse
    {
        try {
            $value = AttributeValue::create($request->validated());
            return $this->successResponse($value,'مقدار ویژگی با موفقیت ایجاد شد',201);
        } catch (\Throwable $e) {
            Log::error('Error creating attribute value: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد مقدار ویژگی');
        }
    }

    public function show(AttributeValue $attributeValue): JsonResponse
    {
        try {
            $attributeValue->load('attribute');
            return $this->successResponse($attributeValue,'جزئیات مقدار ویژگی با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error showing attribute value: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت جزئیات مقدار ویژگی');
        }
    }

    public function update(UpdateAttributeValueRequest $request, AttributeValue $attributeValue): JsonResponse
    {
        try {
            $attributeValue->update($request->validated());
            return $this->successResponse($attributeValue,'مقدار ویژگی با موفقیت بروزرسانی شد');
        } catch (\Throwable $e) {
            Log::error('Error updating attribute value: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی مقدار ویژگی');
        }
    }

    public function destroy(AttributeValue $attributeValue): JsonResponse
    {
        try {
            $attributeValue->delete();
            return $this->successResponse(null,'مقدار ویژگی با موفقیت حذف شد');
        } catch (\Throwable $e) {
            Log::error('Error deleting attribute value: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف مقدار ویژگی');
        }
    }
}

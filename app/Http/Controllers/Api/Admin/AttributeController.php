<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAttributeRequest;
use App\Http\Requests\Admin\UpdateAttributeRequest;
use App\Models\Attribute;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AttributeController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $attributes = Attribute::with('values')->latest()->get();
            return $this->successResponse($attributes, 'لیست Attribute ها با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error fetching attributes: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست Attribute ها');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request): JsonResponse
    {
        try {
            $attribute = Attribute::create($request->validated());
            return $this->successResponse($attribute, 'Attribute با موفقیت ایجاد شد', 201);
        } catch (\Throwable $e) {
            Log::error('Error creating attribute: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد Attribute');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute): JsonResponse
    {
        try {
            return $this->successResponse($attribute, 'جزئیات Attribute با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error showing attribute: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت جزئیات Attribute');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute): JsonResponse
    {
        try {
            $attribute->update($request->validated());
            return $this->successResponse($attribute, 'Attribute با موفقیت بروزرسانی شد');
        } catch (\Throwable $e) {
            Log::error('Error updating attribute: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی Attribute');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute): JsonResponse
    {
        try {
            $attribute->delete();
            return $this->successResponse(null, 'Attribute با موفقیت حذف شد');
        } catch (\Throwable $e) {
            Log::error('Error deleting attribute: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف Attribute');
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $categories = Category::with(['parent', 'children', 'media'])->latest()->get();
            return $this->successResponse($categories, 'لیست دسته‌بندی‌ها با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست دسته‌بندی‌ها');
        }
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $slugBase = Str::slug($data['slug'] ?? $data['name']);
            $data['slug'] = Category::where('slug', $slugBase)->exists()
                ? $slugBase . '-' . rand(100, 999)
                : $slugBase;

            $category = Category::create($data);

            return $this->successResponse($category, 'دسته‌بندی با موفقیت ایجاد شد', 201);
        } catch (\Throwable $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد دسته‌بندی');
        }
    }

    public function show(Category $category): JsonResponse
    {
        try {
            $category->load(['parent', 'children', 'media']);
            return $this->successResponse($category, 'جزئیات دسته‌بندی با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            Log::error('Error showing category: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت جزئیات دسته‌بندی');
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $data = $request->validated();

            if (empty($data['slug'])) {
                $slugBase = Str::slug($data['name']);
                $data['slug'] = Category::where('slug', $slugBase)
                    ->where('id', '!=', $category->id)
                    ->exists()
                    ? $slugBase . '-' . rand(100, 999)
                    : $slugBase;
            }

            $category->update($data);

            return $this->successResponse($category, 'دسته‌بندی با موفقیت بروزرسانی شد');
        } catch (\Throwable $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی دسته‌بندی');
        }
    }

    public function destroy(Category $category): JsonResponse
    {
        try {
            if ($category->children()->exists()) {
                $category->children()->delete();
            }

            $category->delete();

            return $this->successResponse(null, 'دسته‌بندی با موفقیت حذف شد');
        } catch (\Throwable $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف دسته‌بندی');
        }
    }
}

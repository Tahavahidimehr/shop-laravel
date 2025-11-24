<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductReturnItemRequest;
use App\Http\Requests\Admin\UpdateProductReturnItemRequest;
use App\Models\ProductReturnItem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductReturnItemController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $items = ProductReturnItem::latest()->with(['productReturn', 'orderItem', 'warehouse', 'returnable'])->paginate(10);
            return $this->successResponse($items, 'لیست آیتم‌های بازگشت کالا با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching product return items: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست آیتم‌های بازگشت کالا');
        }
    }

    public function store(StoreProductReturnItemRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $item = ProductReturnItem::create($data);
            return $this->successResponse($item, 'آیتم بازگشت کالا با موفقیت ثبت شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating product return item: ' . $e->getMessage());
            return $this->errorResponse('خطا در ثبت آیتم بازگشت کالا');
        }
    }

    public function show(ProductReturnItem $productReturnItem): JsonResponse
    {
        try {
            $productReturnItem->load(['productReturn', 'orderItem', 'warehouse', 'returnable']);
            return $this->successResponse($productReturnItem, 'آیتم بازگشت کالا با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing product return item: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت آیتم بازگشت کالا');
        }
    }

    public function update(UpdateProductReturnItemRequest $request, ProductReturnItem $productReturnItem): JsonResponse
    {
        try {
            $data = $request->validated();
            $productReturnItem->update($data);
            return $this->successResponse($productReturnItem, 'آیتم بازگشت کالا با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating product return item: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی آیتم بازگشت کالا');
        }
    }

    public function destroy(ProductReturnItem $productReturnItem): JsonResponse
    {
        try {
            $productReturnItem->delete();
            return $this->successResponse(null, 'آیتم بازگشت کالا با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting product return item: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف آیتم بازگشت کالا');
        }
    }
}

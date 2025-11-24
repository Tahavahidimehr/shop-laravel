<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductReturnRequest;
use App\Http\Requests\Admin\UpdateProductReturnRequest;
use App\Models\ProductReturn;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductReturnController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $returns = ProductReturn::latest()->with(['order', 'user', 'warehouse'])->paginate(10);
            return $this->successResponse($returns, 'لیست بازگشت کالاها با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching product returns: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست بازگشت کالاها');
        }
    }

    public function store(StoreProductReturnRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $productReturn = ProductReturn::create($data);
            return $this->successResponse($productReturn, 'بازگشت کالا با موفقیت ثبت شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating product return: ' . $e->getMessage());
            return $this->errorResponse('خطا در ثبت بازگشت کالا');
        }
    }

    public function show(ProductReturn $productReturn): JsonResponse
    {
        try {
            $productReturn->load(['order', 'user', 'warehouse', 'items']);
            return $this->successResponse($productReturn, 'بازگشت کالا با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing product return: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت بازگشت کالا');
        }
    }

    public function update(UpdateProductReturnRequest $request, ProductReturn $productReturn): JsonResponse
    {
        try {
            $data = $request->validated();
            $productReturn->update($data);
            return $this->successResponse($productReturn, 'بازگشت کالا با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating product return: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی بازگشت کالا');
        }
    }

    public function destroy(ProductReturn $productReturn): JsonResponse
    {
        try {
            $productReturn->delete();
            return $this->successResponse(null, 'بازگشت کالا با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting product return: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف بازگشت کالا');
        }
    }
}

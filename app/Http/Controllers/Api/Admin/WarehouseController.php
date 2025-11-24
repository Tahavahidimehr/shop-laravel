<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWarehouseRequest;
use App\Http\Requests\Admin\UpdateWarehouseRequest;
use App\Models\Warehouse;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WarehouseController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $warehouses = Warehouse::latest()->paginate(10);
            return $this->successResponse($warehouses, 'لیست انبارها با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching warehouses: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست انبارها');
        }
    }

    public function store(StoreWarehouseRequest $request): JsonResponse
    {
        try {
            $warehouse = Warehouse::create($request->validated());
            return $this->successResponse($warehouse, 'انبار با موفقیت ثبت شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating warehouse: ' . $e->getMessage());
            return $this->errorResponse('خطا در ثبت انبار');
        }
    }

    public function show(Warehouse $warehouse): JsonResponse
    {
        try {
            return $this->successResponse($warehouse, 'انبار با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing warehouse: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت اطلاعات انبار');
        }
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): JsonResponse
    {
        try {
            $warehouse->update($request->validated());
            return $this->successResponse($warehouse, 'انبار با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating warehouse: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی انبار');
        }
    }

    public function destroy(Warehouse $warehouse): JsonResponse
    {
        try {
            $warehouse->delete();
            return $this->successResponse(null, 'انبار با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting warehouse: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف انبار');
        }
    }
}

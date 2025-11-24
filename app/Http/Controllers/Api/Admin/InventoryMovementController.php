<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInventoryMovementRequest;
use App\Http\Requests\Admin\UpdateInventoryMovementRequest;
use App\Models\InventoryMovement;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class InventoryMovementController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $movements = InventoryMovement::latest()->with(['warehouse', 'product', 'productVariant', 'movable'])->paginate(10);
            return $this->successResponse($movements, 'لیست حرکات انبار با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching inventory movements: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست حرکات انبار');
        }
    }

    public function store(StoreInventoryMovementRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $movement = InventoryMovement::create($data);
            return $this->successResponse($movement, 'حرکت انبار با موفقیت ثبت شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating inventory movement: ' . $e->getMessage());
            return $this->errorResponse('خطا در ثبت حرکت انبار');
        }
    }

    public function show(InventoryMovement $inventoryMovement): JsonResponse
    {
        try {
            $inventoryMovement->load(['warehouse', 'product', 'productVariant', 'movable']);
            return $this->successResponse($inventoryMovement, 'حرکت انبار با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing inventory movement: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت حرکت انبار');
        }
    }

    public function update(UpdateInventoryMovementRequest $request, InventoryMovement $inventoryMovement): JsonResponse
    {
        try {
            $data = $request->validated();
            $inventoryMovement->update($data);
            return $this->successResponse($inventoryMovement, 'حرکت انبار با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating inventory movement: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی حرکت انبار');
        }
    }

    public function destroy(InventoryMovement $inventoryMovement): JsonResponse
    {
        try {
            $inventoryMovement->delete();
            return $this->successResponse(null, 'حرکت انبار با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting inventory movement: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف حرکت انبار');
        }
    }
}

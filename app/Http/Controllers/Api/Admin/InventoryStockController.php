<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInventoryStockRequest;
use App\Http\Requests\Admin\UpdateInventoryStockRequest;
use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class InventoryStockController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $stocks = InventoryStock::latest()->with(['warehouse', 'product', 'productVariant'])->paginate(10);
            return $this->successResponse($stocks, 'لیست موجودی‌ها با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching inventory stocks: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست موجودی‌ها');
        }
    }

    public function store(StoreInventoryStockRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $stock = InventoryStock::firstOrCreate(
                [
                    'warehouse_id' => $data['warehouse_id'],
                    'product_id' => $data['product_id'] ?? null,
                    'product_variant_id' => $data['product_variant_id'] ?? null,
                    'stockable_type' => $data['stockable_type'] ?? null,
                    'stockable_id' => $data['stockable_id'] ?? null,
                ],
                ['quantity' => 0, 'average_cost' => 0]
            );

            $movement = InventoryMovement::create([
                'warehouse_id' => $stock->warehouse_id,
                'type' => $data['quantity'] > 0 ? 'in' : 'out',
                'quantity' => abs($data['quantity']),
                'movable_type' => InventoryStock::class,
                'movable_id' => $stock->id,
                'note' => $data['note'] ?? null,
            ]);

            // بروزرسانی موجودی و میانگین قیمت
            $stock->updateStock(abs($data['quantity']), $data['average_cost'] ?? null, $data['quantity'] > 0 ? 'in' : 'out');

            return $this->successResponse($stock, 'موجودی با موفقیت بروزرسانی شد', 201);
        } catch (\Exception $e) {
            Log::error('Error updating inventory stock: ' . $e->getMessage());
            return $this->errorResponse('خطا در ثبت موجودی');
        }
    }

    public function show(InventoryStock $inventoryStock): JsonResponse
    {
        try {
            return $this->successResponse($inventoryStock->load(['warehouse', 'product', 'productVariant']), 'موجودی با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing inventory stock: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت موجودی');
        }
    }

    public function update(UpdateInventoryStockRequest $request, InventoryStock $inventoryStock): JsonResponse
    {
        try {
            $data = $request->validated();

            if (isset($data['quantity']) || isset($data['average_cost'])) {
                $inventoryStock->updateStock($data['quantity'] ?? 0, $data['average_cost'] ?? null, 'in');
            }

            return $this->successResponse($inventoryStock, 'موجودی با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating inventory stock: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی موجودی');
        }
    }

    public function destroy(InventoryStock $inventoryStock): JsonResponse
    {
        try {
            $inventoryStock->delete();
            return $this->successResponse(null, 'موجودی با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting inventory stock: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف موجودی');
        }
    }
}

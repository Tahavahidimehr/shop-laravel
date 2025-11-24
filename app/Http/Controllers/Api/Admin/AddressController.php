<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAddressRequest;
use App\Http\Requests\Admin\UpdateAddressRequest;
use App\Models\Address;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $addresses = Address::latest()->get();
            return $this->successResponse($addresses, 'لیست آدرس‌ها با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching addresses: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست آدرس‌ها');
        }
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        try {
            $address = Address::create($request->validated());
            return $this->successResponse($address, 'آدرس با موفقیت ایجاد شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating address: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد آدرس');
        }
    }

    public function show(Address $address): JsonResponse
    {
        try {
            return $this->successResponse($address, 'آدرس با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing address: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت اطلاعات آدرس');
        }
    }

    public function update(UpdateAddressRequest $request, Address $address): JsonResponse
    {
        try {
            $address->update($request->validated());
            return $this->successResponse($address, 'آدرس با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating address: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی آدرس');
        }
    }

    public function destroy(Address $address): JsonResponse
    {
        try {
            $address->delete();
            return $this->successResponse(null, 'آدرس با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting address: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف آدرس');
        }
    }
}

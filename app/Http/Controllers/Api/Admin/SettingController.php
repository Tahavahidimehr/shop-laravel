<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSettingRequest;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\Setting;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $settings = Setting::latest()->paginate(10);
            return $this->successResponse($settings, 'تنظیمات با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching settings: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت تنظیمات');
        }
    }

    public function store(StoreSettingRequest $request): JsonResponse
    {
        try {
            $setting = Setting::create($request->validated());
            return $this->successResponse($setting, 'تنظیمات با موفقیت ایجاد شد', 201);
        } catch (\Exception $e) {
            Log::error('Error creating setting: ' . $e->getMessage());
            return $this->errorResponse('خطا در ایجاد تنظیمات');
        }
    }

    public function show(Setting $setting): JsonResponse
    {
        try {
            return $this->successResponse($setting, 'تنظیمات با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error showing setting: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت اطلاعات تنظیمات');
        }
    }

    public function update(UpdateSettingRequest $request, Setting $setting): JsonResponse
    {
        try {
            $setting->update($request->validated());
            return $this->successResponse($setting, 'تنظیمات با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            Log::error('Error updating setting: ' . $e->getMessage());
            return $this->errorResponse('خطا در بروزرسانی تنظیمات');
        }
    }

    public function destroy(Setting $setting): JsonResponse
    {
        try {
            $setting->delete();
            return $this->successResponse(null, 'تنظیمات با موفقیت حذف شد');
        } catch (\Exception $e) {
            Log::error('Error deleting setting: ' . $e->getMessage());
            return $this->errorResponse('خطا در حذف تنظیمات');
        }
    }
}

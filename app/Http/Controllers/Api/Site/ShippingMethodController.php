<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingMethodController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $methods = ShippingMethod::where('is_active', true)->get();
            return $this->successResponse($methods, 'لیست روش‌های ارسال با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching shipping methods: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست روش‌های ارسال');
        }
    }
}

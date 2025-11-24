<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        try {
            $addresses = $request->user()->addresses;
            return $this->successResponse($addresses, 'لیست آدرس‌ها با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching addresses: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست آدرس‌ها');
        }
    }
}

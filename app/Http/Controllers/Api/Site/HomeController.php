<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $categories = Category::with('media')
                ->latest()
                ->get();

            $special_products = Product::query()
                ->where('special_offer', true)
                ->where('status', 'active')
                ->with('media')
                ->latest()
                ->take(10)
                ->get();

            $popular_products = Product::query()
                ->where('status', 'active')
                ->orderByDesc('sales_count')
                ->with('media')
                ->take(10)
                ->get();

            return $this->successResponse([
                'categories'          => $categories,
                'specialProducts'     => $special_products,
                'popularProducts'=> $popular_products,
            ], 'اطلاعات صفحه اصلی با موفقیت دریافت شد');
        } catch (\Exception $e) {
            Log::error('Error fetching home page data: ' . $e->getMessage());

            return $this->errorResponse('خطا در دریافت اطلاعات صفحه اصلی');
        }
    }
}

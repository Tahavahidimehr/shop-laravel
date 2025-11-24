<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_ids'   => 'nullable|array',
                'category_ids.*' => 'integer|exists:categories,id',

                'sort'           => 'nullable|in:popular,newest,expensive,cheap',
                'special_offer'  => 'nullable|in:0,1',
                'only_available' => 'nullable|in:0,1',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator, 'ورودی نامعتبر است');
            }

            $categoryIds   = $request->input('category_ids', []);
            $sort          = $request->input('sort', 'popular');
            $specialOffer  = $request->input('special_offer');
            $onlyAvailable = $request->input('only_available');

            $perPage = 12;
            $currentPage = (int) $request->input('page', 1);

            // ۱) فقط فیلترها را روی query اعمال می‌کنیم
            $query = Product::query()
                ->where('status', 'active')

                ->when(!empty($categoryIds), fn($q) =>
                $q->whereIn('category_id', $categoryIds)
                )

                ->when(!is_null($specialOffer), fn($q) =>
                $q->where('special_offer', boolval($specialOffer))
                )

                ->when(!is_null($onlyAvailable), function ($q) use ($onlyAvailable) {
                    if (boolval($onlyAvailable)) {
                        $q->available();
                    }
                })

                // سورت بر اساس قیمت را فعلاً روی DB نمی‌گذاریم، بعداً روی Collection انجام می‌دهیم
                ->with([
                    'media',
                    'variants.inventoryStocks',
                    'inventoryStocks',
                ]);

            // ۲) همه محصولات فیلترشده را می‌گیریم (بدون سورت)
            $productsCollection = $query->get();

            // ۳) سورت روی Collection بر اساس sort_price و بقیه
            $productsCollection = match ($sort) {
                'popular' => $productsCollection
                    ->sortByDesc('sales_count')
                    ->values(),

                'newest' => $productsCollection
                    ->sortByDesc('created_at')
                    ->values(),

                'expensive' => $productsCollection
                    ->sortByDesc(fn (Product $p) => $p->sort_price ?? 0)
                    ->values(),

                'cheap' => $productsCollection
                    ->sortBy(fn (Product $p) => $p->sort_price ?? PHP_INT_MAX)
                    ->values(),

                default => $productsCollection
                    ->sortByDesc('sales_count')
                    ->values(),
            };

            // ۴) دستی paginate می‌کنیم
            $total = $productsCollection->count();
            $items = $productsCollection
                ->forPage($currentPage, $perPage)
                ->values();

            $products = new LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $currentPage,
                [
                    'path'  => $request->url(),
                    'query' => $request->query(),
                ]
            );

            $categories = Category::with('media')->orderBy('name')->get();

            return $this->successResponse([
                'products'   => $products,
                'categories' => $categories,
            ], 'لیست محصولات با موفقیت دریافت شد');

        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت لیست محصولات');
        }
    }

    public function categoryProducts(string $slug, Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'sort'          => 'nullable|in:popular,newest,expensive,cheap',
                'special_offer' => 'nullable|in:0,1',
                'only_available'=> 'nullable|in:0,1',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator, 'ورودی نامعتبر است');
            }

            $sort          = $request->input('sort', 'popular');
            $specialOffer  = $request->input('special_offer');
            $onlyAvailable = $request->input('only_available');

            $perPage = 12;
            $currentPage = (int) $request->input('page', 1);

            $category = Category::where('slug', $slug)->firstOrFail();

            $query = Product::query()
                ->where('status', 'active')
                ->where('category_id', $category->id)

                ->when(!is_null($specialOffer), fn($q) =>
                $q->where('special_offer', boolval($specialOffer))
                )

                ->when(!is_null($onlyAvailable), function ($q) use ($onlyAvailable) {
                    if (boolval($onlyAvailable)) {
                        $q->available();
                    }
                })

                ->with([
                    'media',
                    'variants.inventoryStocks',
                    'inventoryStocks',
                ]);

            $productsCollection = $query->get();

            $productsCollection = match ($sort) {
                'popular' => $productsCollection
                    ->sortByDesc('sales_count')
                    ->values(),

                'newest' => $productsCollection
                    ->sortByDesc('created_at')
                    ->values(),

                'expensive' => $productsCollection
                    ->sortByDesc(fn (Product $p) => $p->sort_price ?? 0)
                    ->values(),

                'cheap' => $productsCollection
                    ->sortBy(fn (Product $p) => $p->sort_price ?? PHP_INT_MAX)
                    ->values(),

                default => $productsCollection
                    ->sortByDesc('sales_count')
                    ->values(),
            };

            $total = $productsCollection->count();
            $items = $productsCollection
                ->forPage($currentPage, $perPage)
                ->values();

            $products = new LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $currentPage,
                [
                    'path'  => $request->url(),
                    'query' => $request->query(),
                ]
            );

            $categories = Category::with('media')->orderBy('name')->get();

            return $this->successResponse([
                'products'   => $products,
                'categories' => $categories,
                'category'   => $category,
            ], 'لیست محصولات دسته با موفقیت دریافت شد');

        } catch (\Exception $e) {
            Log::error('Error fetching category products: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت محصولات این دسته');
        }
    }

    public function productDetail(string $slug, Request $request): JsonResponse
    {
        try {
            $product = Product::query()
                ->where('slug', $slug)
                ->where('status', 'active')
                ->with([
                    'media',
                    'category',
                    'variants.inventoryStocks',
                    'inventoryStocks',
                ])
                ->firstOrFail();

            $product->increment('views_count');

            return $this->successResponse([
                'product' => $product,
            ], 'جزئیات محصول با موفقیت دریافت شد');

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('محصول مورد نظر یافت نشد', null, 404);
        } catch (\Exception $e) {
            Log::error('Error fetching product detail: ' . $e->getMessage());
            return $this->errorResponse('خطا در دریافت جزئیات محصول');
        }
    }
}

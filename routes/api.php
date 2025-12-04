<?php

use App\Http\Controllers\Api\Admin\AttributeController;
use App\Http\Controllers\Api\Admin\AttributeValueController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CartController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\MediaController;
use App\Http\Controllers\Api\Admin\ProductVariantController;
use App\Http\Controllers\Api\Admin\VariantController;
use App\Http\Controllers\Api\Admin\VariantValueController;
use App\Http\Controllers\Api\Site\HomeController;
use App\Http\Controllers\Api\Site\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/verify', [AuthController::class, 'verify']);
Route::prefix('site')->group(function () {
    Route::get('/products', [\App\Http\Controllers\Api\Site\ProductController::class, 'index']);
    Route::get('/categories/{slug}/products', [\App\Http\Controllers\Api\Site\ProductController::class, 'categoryProducts']);
    Route::get('/products/{slug}', [\App\Http\Controllers\Api\Site\ProductController::class, 'productDetail']);
    Route::post('/cart/sync', [\App\Http\Controllers\Api\Site\CartController::class, 'sync']);
    Route::get('/shipping_methods', [\App\Http\Controllers\Api\Site\ShippingMethodController::class, 'index']);
    Route::get('/addresses', [\App\Http\Controllers\Api\Site\AddressController::class, 'index']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::prefix('site')->group(function () {
        Route::get('/shipping_methods', [\App\Http\Controllers\Api\Site\ShippingMethodController::class, 'index']);
        Route::get('/addresses', [\App\Http\Controllers\Api\Site\AddressController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::post('/orders/{order}/pay', [OrderController::class, 'pay']);
    });

    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::apiResource('products', \App\Http\Controllers\Api\Admin\ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('attributes', AttributeController::class);
    Route::apiResource('attributeValues', AttributeValueController::class);
    Route::apiResource('variants', VariantController::class);
    Route::apiResource('variantValues', VariantValueController::class);
    Route::apiResource('productVariants', ProductVariantController::class);
    Route::apiResource('medias', MediaController::class);
    Route::post('medias/sync', [MediaController::class, 'sync']);
});

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Api\Admin\ProductController::class, 'index']);

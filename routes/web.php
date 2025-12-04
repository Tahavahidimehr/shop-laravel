<?php

use App\Http\Controllers\Api\Site\OrderController;
use Illuminate\Support\Facades\Route;

Route::any('/payment/zibal/callback', [OrderController::class, 'callback'])
    ->name('payment.zibal.callback');


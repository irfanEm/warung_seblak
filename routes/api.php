<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payment/notification', [\App\Http\Controllers\Api\PaymentNotificationController::class, '__invoke'])
    ->middleware('throttle:10,1');

<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    Route::post('/orders', [OrderController::class, 'store']);

    Route::post('/orders/{order}/products/{product}', [OrderController::class, 'addProduct']);
    Route::delete('/orders/{order}/products/{product}', [OrderController::class, 'removeProduct']);

    Route::get('/products', [ProductController::class, 'index']);
})->middleware('auth:sanctum');
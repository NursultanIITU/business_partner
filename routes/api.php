<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->controller(AuthController::class)->middleware(['api'])->group(function() {
        Route::post('/login',  'login');
        Route::post('/register', 'register');
        Route::post('/refresh', 'refresh');
        Route::post('/logout', 'logout');
        Route::get('/profile', 'profile');
    });
    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::get('/', 'getAll');
        Route::get('/product/{productId}', 'getById')->whereNumber(['productId']);
        Route::post('/product/{productId}/add-to-cart', 'addToCart')->whereNumber(['productId']);
        Route::get('/my-cart', 'myCart');
        Route::delete('/my-cart/{cartId}', 'deleteCart')->whereNumber(['cartId']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

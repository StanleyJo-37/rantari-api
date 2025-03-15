<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Route::group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductController::class, 'getAllProducts']);
        Route::get('/{id}', [ProductController::class, 'getProductDetail']);
        Route::post('/', [ProductController::class, 'createProduct']);
        Route::patch('/{product_id}', [ProductController::class, 'editProduct']);
        Route::delete('/{product_id}', [ProductController::class, 'deleteProduct']);
    });

    Route::prefix('/orders')->group(function () {
        Route::get('/', [OrderController::class, 'getAllOrders']);
    });
// });

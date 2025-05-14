<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtherAuth\GoogleAuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'indexLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'indexRegister'])->name('register');
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::prefix('api')->group(function () {
    Route::get('google/redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
    Route::get('google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
})->middleware('api');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('menu');

    Route::prefix('api')->group(function () {
        Route::get('products-by-category', [IndexController::class, 'getProductsByCategory'])->name('product.by-category');
        Route::get('products-search', [IndexController::class, 'searchProducts'])->name('product.search');
        Route::get('tables-all', [IndexController::class, 'getAllTables'])->name('table.get-all');
        Route::get('/cart/{tableId}', [IndexController::class, 'getCartByTable'])->name('cart.by-table');
        Route::post('/cart', [IndexController::class, 'saveCart'])->name('cart.save');
        Route::put('/cart/{cart}', [IndexController::class, 'updateCart'])->name('cart.update');

        Route::post('/orders', [IndexController::class, 'orderStore'])->name('order.store');
    });

    Route::get('/orders/print-content/{id}', [IndexController::class, 'printContent'])->name('print.bill');

    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('order', [\App\Http\Controllers\Admin\OrderManagementController::class, 'index'])->name('admin.orders');
    });

    Route::prefix('api')->group(function () {
        Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderManagementController::class, 'show'])->name('order.show');
        Route::put('/orders/{id}', [\App\Http\Controllers\Admin\OrderManagementController::class, 'update'])->name('order.update');       // Cập nhật đơn hàng
        Route::delete('/orders/{id}', [\App\Http\Controllers\Admin\OrderManagementController::class, 'destroy'])->name('order.destroy');
    });


});


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
    Route::post('/register/request', [AuthController::class, 'registerRequest'])->name('register.request');
    Route::post('/register/verify', [AuthController::class, 'verifyOtp'])->name('register.verify');

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
        Route::get('products', [\App\Http\Controllers\Admin\ProductManagerController::class, 'index'])->name('admin.products');
        Route::get('categories', [\App\Http\Controllers\Admin\CategoriesMangementController::class, 'index'])->name('admin.categories');
        Route::get('users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users');
    });

    Route::prefix('api')->group(function () {
        Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderManagementController::class, 'show'])->name('order.show');
        Route::put('/orders/{id}', [\App\Http\Controllers\Admin\OrderManagementController::class, 'update'])->name('order.update');       // Cập nhật đơn hàng
        Route::delete('/orders/{id}', [\App\Http\Controllers\Admin\OrderManagementController::class, 'destroy'])->name('order.destroy');

        Route::post('/products', [\App\Http\Controllers\Admin\ProductManagerController::class, 'store'])->name('product.store');
        Route::get('/products/{id}', [\App\Http\Controllers\Admin\ProductManagerController::class, 'show'])->name('product.show');
        Route::put('/products/{id}', [\App\Http\Controllers\Admin\ProductManagerController::class, 'update'])->name('product.update');
        Route::delete('/products/{id}', [\App\Http\Controllers\Admin\ProductManagerController::class, 'destroy'])->name('product.destroy');

        Route::get('categories/{id}', [\App\Http\Controllers\Admin\CategoriesMangementController::class, 'show'])->name('category.show');
        Route::post('categories', [\App\Http\Controllers\Admin\CategoriesMangementController::class, 'store'])->name('category.store');
        Route::put('categories/{id}', [\App\Http\Controllers\Admin\CategoriesMangementController::class, 'update'])->name('category.update');
        Route::delete('categories/{id}', [\App\Http\Controllers\Admin\CategoriesMangementController::class, 'destroy'])->name('category.destroy');

        Route::get('/users/{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('user.show');
        Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('user.update');
        Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('user.destroy');
        Route::post('/users/{id}/approve', [\App\Http\Controllers\Admin\UserManagementController::class, 'approveUser'])->name('user.approve');
    });
});


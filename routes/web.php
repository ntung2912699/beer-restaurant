<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [IndexController::class, 'index'])->name('menu');

Route::prefix('auth')->group(function () {
    Route::get('google/redirect', [GoogleAuthController::class, 'redirectToGoogle']);
    Route::get('google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
});

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
});



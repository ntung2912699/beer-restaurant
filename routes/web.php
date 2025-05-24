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
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('menu');
    Route::get('/orders/print-content/{id}', [IndexController::class, 'printContent'])->name('print.bill');
    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('order', [\App\Http\Controllers\Admin\OrderManagementController::class, 'index'])->name('admin.orders');
        Route::get('products', [\App\Http\Controllers\Admin\ProductManagerController::class, 'index'])->name('admin.products');
        Route::get('categories', [\App\Http\Controllers\Admin\CategoriesMangementController::class, 'index'])->name('admin.categories');
        Route::get('users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users');
    });
});


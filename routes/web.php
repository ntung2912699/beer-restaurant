<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\IndexController;

Route::get('/', [IndexController::class, 'index']);

Route::prefix('auth')->group(function () {
    Route::get('google/redirect', [GoogleAuthController::class, 'redirectToGoogle']);
    Route::get('google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
});

Route::prefix('api')->group(function () {
    Route::get('products-by-category', [IndexController::class, 'getProductsByCategory']);
    Route::get('products-search', [IndexController::class, 'searchProducts']);
    Route::get('tables-all', [IndexController::class, 'getAllTables']);
    Route::get('/cart/{tableId}', [IndexController::class, 'getCartByTable']);
    Route::post('/cart', [IndexController::class, 'saveCart']);
    Route::put('/cart/{cart}', [IndexController::class, 'updateCart']);

    Route::post('/orders', [IndexController::class, 'orderStore']);

});
Route::get('/orders/print-content/{id}', [IndexController::class, 'printContent']);



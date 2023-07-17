<?php

use App\Http\Controllers\Client\CartController;
use Illuminate\Support\Facades\Route;


Route::prefix('cart/')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('add-to-cart/{productId}/{qty?}', [CartController::class, 'addProductToCart'])->name('add-to-cart');
    Route::get('delete-product-in-cart/{productId}', [CartController::class, 'deleteProductInCart'])->name('delete-product-in-cart');
});

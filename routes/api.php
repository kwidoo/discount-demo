<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::get('/products', [ProductController::class, 'index']);
Route::post('/cart/calculate/{cart?}', [CartController::class, 'calculate']);
Route::get('/cart/{cart?}', [CartController::class, 'getCart']);

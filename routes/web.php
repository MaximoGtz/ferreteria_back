<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SellController;

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Ruta para mostrar productos en el carrito
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/cart', [ProductController::class, 'index'])->name('cart.index');

// Ruta para agregar productos al carrito

// Ruta para obtener productos en el carrito
Route::get('/mi-vista', [CartController::class, 'get']);

Route::get('/product/show/{id}',[ProductController::class,'show']);



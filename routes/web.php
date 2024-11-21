<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Ruta para mostrar productos en el carrito
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/cart', [ProductController::class, 'index'])->name('cart.index');

// Ruta para agregar productos al carrito

// Ruta para obtener productos en el carrito
Route::get('/get/cart', [CartController::class, 'get'])->name('cart.get');

Route::get('/product/show/{id}',[ProductController::class,'show']);

Route::post('/add/cart', [CartController::class, 'add'])->name('cart.add');
Route::post('/quit/cart/{id}', [CartController::class, 'quitItem'])->name('cart.quit');
Route::post('/more/cart', [CartController::class, 'more'])->name('cart.more');
Route::post('/less/cart', [CartController::class, 'less'])->name('cart.less');
Route::post('/clear/cart', [CartController::class, 'clear'])->name('cart.clear');


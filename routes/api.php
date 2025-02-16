<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Cart;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('brands', BrandController::class);
Route::apiResource('directions', DirectionController::class);
Route::apiResource('users', UserController::class);


Route::get('/search/product/{search}', [ProductController::class, 'searchname']);


// Route::post
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::prefix('sells')->group(function () {
    Route::get('/', [SellController::class, 'index']);        // Obtener todas las ventas
    Route::get('/{id}', [SellController::class, 'show']);     // Obtener una venta específica
    Route::post('/{id}', [SellController::class, 'store']);       // Crear una nueva venta
    Route::delete('/{id}', [SellController::class, 'destroy']); // Eliminar una venta específica
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'get']);
    Route::get('/client/{id}', [CartController::class, 'show']);
    Route::post('/add', [CartController::class, 'add']);
    Route::delete('/{id}', [CartController::class, 'quitItem']);
    Route::put('/{id}/more', [CartController::class, 'more']);
    Route::put('/{id}/less', [CartController::class, 'less']);
});

Route::delete('/clear', [CartController::class, 'clear']);
Route::delete('/clear', [CartController::class, 'clear']);

Route::get('comment/{productId}', [CommentController::class, 'getProductRating']);
Route::post('comment/', [CommentController::class, 'store']);

Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->stateless()->redirect();
});

Route::get('/normalcomment', [ContactController::class, 'getContacts']);
Route::get('/normalcomment/{id}', [ContactController::class, 'getContact']);
Route::post('/normalcomment', [ContactController::class, 'store']);

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::updateOrCreate(
        ['google_id' => $googleUser->id],
        [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'image' => $googleUser->avatar,
        ]
    );

    $token = $user->createToken($user->name)->plainTextToken;

    return response()->json([
        "user" => $user,
        "token" => $token
    ]);
});

// ! RUTAS DE API EN PRUEBAS

Route::post('cart/paypal/create', [CartController::class, 'createPaypalOrder']);
Route::get('paypal/return', [CartController::class, 'paypalReturn']);
Route::get('paypal/cancel', [CartController::class, 'paypalCancel']);


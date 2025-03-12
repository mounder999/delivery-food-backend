<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/foods', [FoodController::class, 'create']);
Route::get('/foods/{id}', [FoodController::class, 'show']);
Route::get('/foodsget', [FoodController::class, 'index']);
Route::delete('/foodsdelete/{id}', [FoodController::class, 'delete']);
Route::put('/foodsupdate/{id}', [FoodController::class, 'update']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
 use App\Http\Controllers\CartController;
 Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [CartController::class, 'addToCart']); // Add to cart
    Route::post('/cart/remove/{foodId}', [CartController::class, 'removeFromCart']); // Remove item from cart
    Route::get('/cart', [CartController::class, 'viewCart']); // View cart
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);
    Route::post('/orders/place', [OrderController::class, 'placeOrder']); // Clear the cart
});
 


   // Route::post('/cart/add', [CartController::class, 'addToCart']);
//     Route::delete('/cart/remove/{foodId}', [CartController::class, 'removeFromCart']);
//     Route::get('/cart', [CartController::class, 'viewCart']);
//     Route::delete('/cart/clear', [CartController::class, 'clearCart']);



// API Routes for Authentication

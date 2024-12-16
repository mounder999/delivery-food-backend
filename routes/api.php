<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/foods', [FoodController::class, 'create']);
Route::get('/foods/{id}', [FoodController::class, 'show']);
Route::get('/foodsget', [FoodController::class, 'index']);
Route::delete('/foodsdelete/{id}', [FoodController::class, 'delete']);
Route::put('/foodsupdate/{id}', [FoodController::class, 'update']);

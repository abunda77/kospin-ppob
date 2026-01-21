<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdukPpobController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/produk-ppob', [ProdukPpobController::class, 'index'])->middleware('throttle:60,1');

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

<?php

use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Administrator-only routes
Route::middleware(['auth', 'verified', 'administrator'])->group(function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
});

require __DIR__.'/settings.php';

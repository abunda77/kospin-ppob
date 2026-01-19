<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukPpobController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SubKategoriController;
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

    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');

});

// Master Data routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/sub-kategori', [SubKategoriController::class, 'index'])->name('sub-kategori.index');
    Route::get('/produk-ppob', [ProdukPpobController::class, 'index'])->name('produk-ppob.index');
});

require __DIR__.'/settings.php';

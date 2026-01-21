<?php

use App\Http\Controllers\BackupDatabaseController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\NetworkConnectionController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganExportController;
use App\Http\Controllers\ProdukPpobController;
use App\Http\Controllers\ProdukPpobExportController;
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
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');

    // Produk PPOB Export Routes
    Route::get('/produk-ppob/export/excel', [ProdukPpobExportController::class, 'exportExcel'])->name('produk-ppob.export.excel');
    Route::get('/produk-ppob/export/pdf', [ProdukPpobExportController::class, 'exportPdf'])->name('produk-ppob.export.pdf');
    Route::get('/produk-ppob/export/template', [ProdukPpobExportController::class, 'downloadTemplate'])->name('produk-ppob.export.template');

    // Pelanggan Export Routes
    Route::get('/pelanggan/export/excel', [PelangganExportController::class, 'exportExcel'])->name('pelanggan.export.excel');
    Route::get('/pelanggan/export/pdf', [PelangganExportController::class, 'exportPdf'])->name('pelanggan.export.pdf');
    Route::get('/pelanggan/export/template', [PelangganExportController::class, 'downloadTemplate'])->name('pelanggan.export.template');
});

// Network Connection routes
Route::middleware(['auth', 'verified', 'can:network.view'])->group(function () {
    Route::get('/network/sign-on-vps', [NetworkConnectionController::class, 'signOnVps'])->name('network.sign-on-vps');
    Route::get('/network/start-tunnel', [NetworkConnectionController::class, 'startTunnel'])->name('network.start-tunnel');
    Route::get('/network/stop-tunnel', [NetworkConnectionController::class, 'stopTunnel'])->name('network.stop-tunnel');
    Route::get('/network/check-ip', [NetworkConnectionController::class, 'checkIp'])->name('network.check-ip');
    Route::get('/network/check-port', [NetworkConnectionController::class, 'checkPort'])->name('network.check-port');
    Route::get('/network/verify-environment', [NetworkConnectionController::class, 'verifyEnvironment'])->name('network.verify-environment');
});

// Tools routes
Route::middleware(['auth', 'verified', 'can:backup.view'])->group(function () {
    Route::get('/backup-database', [BackupDatabaseController::class, 'index'])->name('backup-database.index');
    Route::get('/backup-database/download/{id}', [BackupDatabaseController::class, 'download'])->name('backup-database.download');
});

require __DIR__.'/settings.php';

<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\CuciGudangController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('/kategori', KategoriController::class);
Route::resource('/produk', ProdukController::class);
Route::resource('/stok', StokController::class);
Route::resource('/cuci_gudang', CuciGudangController::class);
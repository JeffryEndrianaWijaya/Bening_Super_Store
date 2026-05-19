<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\CuciGudangController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PesananFrontController;
use App\Http\Controllers\PesananAdminController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\UlasanAdminController;
use Illuminate\Support\Facades\Route;

// ===== FRONTEND (Pelanggan) =====
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/category/{id?}', [FrontendController::class, 'category'])->name('shop.category.index');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Keranjang & Checkout (harus login)
Route::middleware('auth')->group(function () {
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::patch('/keranjang/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');

    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/pesanan/update-status-after-pay', [CheckoutController::class, 'updateStatusAfterPay'])->name('pesanan.update_status_after_pay');

    Route::get('/pesanan', [PesananFrontController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [PesananFrontController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{id}/konfirmasi', [PesananFrontController::class, 'konfirmasi'])->name('pesanan.konfirmasi');
    Route::post('/pesanan/{id}/simulasi-bayar', [PesananFrontController::class, 'simulasiBayar'])->name('pesanan.simulasi_bayar');
});

// Midtrans Callback (no auth needed)
Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');

// ===== BACKEND (Admin Panel) =====
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/kategori', KategoriController::class);
    Route::resource('/produk', ProdukController::class);
    Route::resource('/stok', StokController::class);
    Route::resource('/cuci_gudang', CuciGudangController::class);
    Route::resource('/pesanan_admin', PesananAdminController::class);
    Route::resource('/user_admin', UserAdminController::class);
    Route::resource('/ulasan_admin', UlasanAdminController::class);
});
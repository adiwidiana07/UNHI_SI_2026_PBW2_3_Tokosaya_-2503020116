<?php

use App\Http\Controllers\BackOffice\AuthController;
use App\Http\Controllers\BackOffice\DashboardController;
use App\Http\Controllers\BackOffice\KategoriController;
use App\Http\Controllers\BackOffice\PesananController as PesananBackOffice;
use App\Http\Controllers\BackOffice\ProdukController as ProdukBackOffice;
use App\Http\Controllers\FrontOffice\BahasaController;
use App\Http\Controllers\FrontOffice\CheckoutController;
use App\Http\Controllers\FrontOffice\HalamanController;
use App\Http\Controllers\FrontOffice\KeranjangController;
use App\Http\Controllers\FrontOffice\ProdukController;
use App\Http\Controllers\MidtransNotifikasiController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;

Route::get('/bahasa/{kode}', [BahasaController::class, 'ganti'])->name('bahasa.ganti');

Route::get('/', [HalamanController::class, 'home'])->name('home');
Route::get('/kontak', [HalamanController::class, 'kontak'])->name('kontak');

// Dipanggil server Midtrans, bukan browser, sehingga tidak membawa token CSRF.
Route::post('/midtrans/notifikasi', MidtransNotifikasiController::class)
    ->withoutMiddleware([PreventRequestForgery::class])
    ->name('midtrans.notifikasi');

Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
// {produk:slug} artinya: carikan produk berdasarkan kolom slug
Route::get('/produk/{produk:slug}', [ProdukController::class, 'show'])->name('produk.show');

Route::prefix('back-office')->name('back-office.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.authenticate');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('admin')
        ->name('dashboard');

    Route::middleware('admin')->group(function () {
        Route::resource('kategori', KategoriController::class)->except(['show']);
        Route::resource('produk', ProdukBackOffice::class)->except(['show']);

        Route::resource('pesanan', PesananBackOffice::class)->only(['index', 'show']);
        Route::patch('pesanan/{pesanan}/status', [PesananBackOffice::class, 'ubahStatus'])
            ->name('pesanan.status');
    });
});

Route::controller(KeranjangController::class)->prefix('keranjang')->name('keranjang.')->group(function () {

    Route::get('/', 'index')->name('index');
    Route::post('/tambah/{produk}', 'tambah')->name('tambah');
    Route::patch('/{produk}', 'ubah')->name('ubah');
    Route::delete('/{produk}', 'hapus')->name('hapus');
    Route::delete('/', 'kosongkan')->name('kosongkan');

});
Route::controller(CheckoutController::class)
    ->prefix('checkout')
    ->name('checkout.')
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::post('/', 'proses')->name('proses');

        Route::get('/selesai/{pesanan:kode_pesanan}', 'selesai')
            ->name('selesai');

        Route::get('/belum-bayar/{pesanan:kode_pesanan}', 'belumBayar')
            ->name('belum_bayar');

        Route::post('/bayar/{pesanan:kode_pesanan}', 'bayar')
            ->middleware('throttle:5,1')
            ->name('bayar');
    });

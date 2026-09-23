<?php

use App\Http\Controllers\BackOffice\AuthController;
use App\Http\Controllers\BackOffice\DashboardController;
use App\Http\Controllers\HalamanController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HalamanController::class, 'home'])->name('home');
Route::get('/kontak', [HalamanController::class, 'kontak'])->name('kontak');

Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

Route::prefix('back-office')->name('back-office.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.authenticate');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('admin')
        ->name('dashboard');
});

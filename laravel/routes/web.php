<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MerekController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Auth\PegawaiLoginController;
use App\Http\Controllers\Auth\PelangganLoginController;
use App\Http\Controllers\KatalogController;
use App\Http\Middleware\CekJabatan;
use App\Models\Pegawai;

// ---------- Pembeli ----------
Route::get('/', fn() => redirect()->route('pegawai.login'));
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
Route::get('/login', [PelangganLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PelangganLoginController::class, 'login'])->name('login.proses');
Route::post('/logout', [PelangganLoginController::class, 'logout'])->name('logout');

// ---------- Pegawai ----------
Route::get('/pegawai/login', [PegawaiLoginController::class, 'showLoginForm'])->name('pegawai.login');
Route::post('/pegawai/login', [PegawaiLoginController::class, 'login'])->name('pegawai.login.proses');
Route::post('/pegawai/logout', [PegawaiLoginController::class, 'logout'])->name('pegawai.logout');

Route::middleware('auth:pegawai')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.produk.index'))->name('dashboard');

    // Semua pegawai boleh melihat daftar
    Route::resource('produk', ProdukController::class)->only(['index']);
    Route::resource('kategori', KategoriController::class)->only(['index']);
    Route::resource('merek', MerekController::class)->only(['index']);

    // Hanya Manajer & Gudang yang boleh tambah / ubah / hapus
    Route::middleware(CekJabatan::class . ':' . implode(',', Pegawai::KELOLA_PRODUK))->group(function () {
        Route::resource('produk', ProdukController::class)->except(['index', 'show']);
        Route::resource('kategori', KategoriController::class)->except(['index', 'show']);
        Route::resource('merek', MerekController::class)->except(['index', 'show']);
    });
});

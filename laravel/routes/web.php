<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MerekController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Auth\PegawaiLoginController;
use App\Http\Controllers\Auth\PelangganRegisterController;
use App\Http\Controllers\Auth\PelangganLoginController;
use App\Http\Controllers\KatalogController;
use App\Http\Middleware\CekJabatan;
use App\Models\Pegawai;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PelangganController; 
use App\Http\Controllers\DashboardPelangganController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\GaransiController;
use App\Http\Controllers\Admin\TransaksiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// ---------- Pembeli ----------



Route::get('/', fn () => redirect()->route('pegawai.login'));
// ---------- Pembeli ----------
Route::get('/daftar', [PelangganRegisterController::class, 'showRegisterForm'])->name('daftar');
Route::post('/daftar', [PelangganRegisterController::class, 'register'])->middleware('throttle:10,1')->name('daftar.proses');
Route::get('/', fn() => redirect()->route('pegawai.login'));
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::view('/tentang', 'tentang')->name('tentang');
Route::get('/login', [PelangganLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PelangganLoginController::class, 'login'])->name('login.proses');
Route::post('/logout', [PelangganLoginController::class, 'logout'])->name('logout');

//Dashboard
Route::get('/', [DashboardPelangganController::class, 'index'])->name('beranda');


// ---------- Media ----------
Route::get('/media/{path}', function (string $path) {
    abort_unless(str_starts_with($path, 'produk/') && Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('media');

// ---------- Pegawai ----------
Route::get('/pegawai/login', [PegawaiLoginController::class, 'showLoginForm'])->name('pegawai.login');
Route::post('/pegawai/login', [PegawaiLoginController::class, 'login'])->name('pegawai.login.proses');
Route::post('/pegawai/logout', [PegawaiLoginController::class, 'logout'])->name('pegawai.logout');

Route::middleware('auth:pegawai')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    // Hanya Manajer yang boleh mengelola pegawai
    Route::middleware(CekJabatan::class . ':' . implode(',', Pegawai::KELOLA_PEGAWAI))->group(function () {
        Route::resource('pegawai', PegawaiController::class)->except(['show']);
    });

        // Manajer & Kasir: lihat, tambah, ubah pelanggan (tanpa hapus)
    Route::middleware(CekJabatan::class . ':' . implode(',', Pegawai::KELOLA_PELANGGAN))->group(function () {
        Route::resource('pelanggan', PelangganController::class)->except(['show', 'destroy']);
    });

    Route::middleware(CekJabatan::class . ':' . implode(',', Pegawai::KELOLA_TRANSAKSI))->group(function () {
        Route::put('transaksi/{transaksi}/pengiriman', [\App\Http\Controllers\Admin\TransaksiController::class, 'pengiriman'])->name('transaksi.pengiriman');
        Route::resource('transaksi', \App\Http\Controllers\Admin\TransaksiController::class)->only(['index', 'show', 'update']);
    });

    Route::middleware(CekJabatan::class . ':' . implode(',', Pegawai::KELOLA_LAPORAN))->group(function () {
        Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('laporan/penjualan/csv', [LaporanController::class, 'penjualanCsv'])->name('laporan.penjualan.csv');
    });

    Route::middleware(CekJabatan::class . ':' . implode(',', Pegawai::KELOLA_KLAIM))->group(function () {
        Route::resource('klaim', \App\Http\Controllers\Admin\KlaimController::class)->only(['index', 'show', 'update']);
    });
});

        // Keranjang (publik)
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah/{produk}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::put('/keranjang/{produk}', [KeranjangController::class, 'ubah'])->name('keranjang.ubah');
    Route::delete('/keranjang/{produk}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');

    // Pesanan (pelanggan harus login)
    Route::middleware('auth:pelanggan')->prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/', [PesananController::class, 'index'])->name('index');
        Route::post('/', [PesananController::class, 'store'])->name('store');
        Route::get('/{transaksi}', [PesananController::class, 'show'])->name('show');
        Route::post('/{transaksi}/batal', [PesananController::class, 'batal'])->name('batal');
        Route::post('/{transaksi}/bukti', [PesananController::class, 'unggahBukti'])->name('bukti');
        Route::post('/{transaksi}/terima', [PesananController::class, 'terima'])->name('terima');
        
    });

    Route::middleware('auth:pelanggan')->prefix('garansi')->name('garansi.')->group(function () {
        Route::get('/', [GaransiController::class, 'index'])->name('index');
        Route::get('/{garansi}/klaim', [GaransiController::class, 'formKlaim'])->name('klaim.form');
        Route::post('/{garansi}/klaim', [GaransiController::class, 'ajukanKlaim'])->name('klaim.store');
    });


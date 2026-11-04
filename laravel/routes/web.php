<?php
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Auth\PegawaiLoginController;
use App\Http\Controllers\Auth\PelangganLoginController;
use App\Http\Controllers\KatalogController;
use App\Http\Middleware\CekJabatan;
use App\Models\Pegawai;

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
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
Route::get('/login', [PelangganLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PelangganLoginController::class, 'login'])->name('login.proses');
Route::post('/logout', [PelangganLoginController::class, 'logout'])->name('logout');

// ---------- Pegawai ----------
Route::get('/pegawai/login', [PegawaiLoginController::class, 'showLoginForm'])->name('pegawai.login');
Route::post('/pegawai/login', [PegawaiLoginController::class, 'login'])->name('pegawai.login.proses');
Route::post('/pegawai/logout', [PegawaiLoginController::class, 'logout'])->name('pegawai.logout');

Route::middleware('auth:pegawai')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.produk.index'))->name('dashboard');

    // Semua pegawai boleh melihat daftar produk
    Route::resource('produk', ProdukController::class)->only(['index']);

    // Hanya Manajer & Gudang yang boleh tambah / ubah / hapus
    Route::middleware(CekJabatan::class . ':' . implode(',', Pegawai::KELOLA_PRODUK))->group(function () {
        Route::resource('produk', ProdukController::class)->except(['index', 'show']);
    });
});

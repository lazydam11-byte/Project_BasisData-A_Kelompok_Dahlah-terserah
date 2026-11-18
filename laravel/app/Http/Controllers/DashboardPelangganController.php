<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\Kategori;
use App\Models\Merek;
use App\Models\Produk;

class DashboardPelangganController extends Controller
{
    /** Halaman beranda publik: bisa dibuka siapa saja, login atau tidak. */
    public function index()
    {
        // 4 produk terbaru yang stoknya masih ada
        $produkBaru = Produk::with(['kategori', 'merek'])
            ->where('stok', '>', 0)
            ->orderByDesc('tanggal_masuk')
            ->limit(4)
            ->get();

        $daftarKategori = Kategori::orderBy('nama_kategori')->get();

        $statistik = [
            'produk_tersedia' => Produk::where('stok', '>', 0)->count(),
            'kategori'        => $daftarKategori->count(),
            'merek'           => Merek::count(),
        ];

        // Pakai batas yang sama dengan dashboard admin
        $batasStok = DashboardController::BATAS_STOK_MENIPIS;

        return view('katalog.dashboard', compact('produkBaru', 'daftarKategori', 'statistik', 'batasStok'));
    }
}
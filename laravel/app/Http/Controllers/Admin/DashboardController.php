<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /** Stok <= angka ini dianggap menipis. Ubah sesuai kebutuhan toko. */
    public const BATAS_STOK_MENIPIS = 5;

    public function index()
    {
        $batas = self::BATAS_STOK_MENIPIS;

        $ringkasan = [
            'total_produk'     => Produk::count(),
            'total_unit'       => (int) Produk::sum('stok'),
            'nilai_persediaan' => (float) Produk::sum(DB::raw('harga * stok')),
            'stok_habis'       => Produk::where('stok', 0)->count(),
            'stok_menipis'     => Produk::where('stok', '>', 0)->where('stok', '<=', $batas)->count(),
            // null = tabel pelanggan belum ada, kartunya disembunyikan
            'total_pelanggan'  => Schema::hasTable('pelanggan') ? DB::table('pelanggan')->count() : null,
        ];

        // Produk yang perlu di-restock: habis dulu, lalu yang paling sedikit
        $perluRestock = Produk::with(['kategori', 'merek'])
            ->where('stok', '<=', $batas)
            ->orderBy('stok')
            ->orderBy('nama_produk')
            ->limit(8)
            ->get();

        // Total stok per kategori (untuk grafik batang)
        $namaKategori = Kategori::pluck('nama_kategori', 'id_kategori');
        $stokPerKategori = Produk::select('id_kategori', DB::raw('COUNT(*) as jumlah_produk'), DB::raw('SUM(stok) as total_stok'))
            ->groupBy('id_kategori')
            ->orderByDesc('total_stok')
            ->get()
            ->map(function ($row) use ($namaKategori) {
                $row->nama = $namaKategori[$row->id_kategori] ?? 'Tanpa kategori';
                return $row;
            });

        $produkTerbaru = Produk::with(['kategori', 'merek'])
            ->orderByDesc('tanggal_masuk')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'ringkasan', 'perluRestock', 'stokPerKategori', 'produkTerbaru', 'batas'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Merek;
use App\Models\Produk;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    /** Katalog publik: tamu dan pelanggan yang login bisa melihat. */
    public function index(Request $request)
    {
        $produk = Produk::with(['kategori', 'merek'])
            ->cari($request->input('q'))
            ->when($request->filled('id_kategori'), fn ($q) => $q->where('id_kategori', $request->input('id_kategori')))
            ->when($request->filled('id_merek'), fn ($q) => $q->where('id_merek', $request->input('id_merek')))
            ->when($request->boolean('stok_tersedia'), fn ($q) => $q->where('stok', '>', 0))
            ->latest('tanggal_masuk')
            ->paginate(12)
            ->withQueryString();

        return view('katalog.index', [
            'produk' => $produk,
            'daftarKategori' => Kategori::orderBy('nama_kategori')->get(),
            'daftarMerek' => Merek::orderBy('nama_merek')->get(),
        ]);
    }
}

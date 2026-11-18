<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    /** Keranjang disimpan di session sebagai [id_produk => jumlah]. */
    public function index()
    {
        $keranjang = session('keranjang', []);
        $pk = (new Produk)->getKeyName();

        $baris = Produk::with(['kategori', 'merek'])
            ->whereIn($pk, array_keys($keranjang))
            ->get()
            ->map(fn ($p) => [
                'produk'   => $p,
                'jumlah'   => $keranjang[$p->getKey()],
                'subtotal' => $p->harga * $keranjang[$p->getKey()],
            ]);

        return view('keranjang.index', [
            'baris'  => $baris,
            'total'  => $baris->sum('subtotal'),
            'metode' => TransaksiPenjualan::METODE,
        ]);
    }

    public function tambah(Produk $produk)
    {
        if ($produk->stok < 1) {
            return back()->with('error', $produk->nama_produk . ' sedang habis.');
        }

        $keranjang = session('keranjang', []);
        $id = $produk->getKey();
        $keranjang[$id] = min(($keranjang[$id] ?? 0) + 1, $produk->stok);
        session(['keranjang' => $keranjang]);

        return back()->with('sukses', $produk->nama_produk . ' ditambahkan ke keranjang.');
    }

    public function ubah(Request $request, Produk $produk)
    {
        $jumlah = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1', 'max:' . max($produk->stok, 1)],
        ], [
            'jumlah.max' => 'Jumlah melebihi stok yang tersedia (' . $produk->stok . ' unit).',
        ])['jumlah'];

        $keranjang = session('keranjang', []);
        $keranjang[$produk->getKey()] = $jumlah;
        session(['keranjang' => $keranjang]);

        return back();
    }

    public function hapus(Produk $produk)
    {
        $keranjang = session('keranjang', []);
        unset($keranjang[$produk->getKey()]);
        session(['keranjang' => $keranjang]);

        return back()->with('sukses', $produk->nama_produk . ' dihapus dari keranjang.');
    }
}
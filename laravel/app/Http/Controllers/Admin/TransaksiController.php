<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garansi;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $transaksi = TransaksiPenjualan::with('pelanggan')
            ->when($request->status, fn ($q, $s) => $q->where('status_pembayaran', $s))
            ->when($request->q, function ($query, $q) {
                $query->where(function ($w) use ($q) {
                    $w->where('id_transaksi', $q)
                      ->orWhereHas('pelanggan', fn ($p) => $p->where('nama', 'like', "%{$q}%"));
                });
            })
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('admin.transaksi.index', [
            'transaksi'    => $transaksi,
            'daftarStatus' => [TransaksiPenjualan::BELUM_DIBAYAR, TransaksiPenjualan::LUNAS, TransaksiPenjualan::DIBATALKAN],
        ]);
    }

    public function show(TransaksiPenjualan $transaksi)
    {
        $transaksi->load(['pelanggan', 'pegawai', 'detail.produk']);

        return view('admin.transaksi.show', compact('transaksi'));
    }

    /** Konfirmasi pembayaran ('lunas') atau batalkan pesanan ('batal'). */
    public function update(Request $request, TransaksiPenjualan $transaksi)
    {
        $aksi = $request->validate([
            'aksi' => ['required', Rule::in(['lunas', 'batal'])],
        ])['aksi'];

        $idPegawai = auth('pegawai')->id();

        if (! $transaksi->bisaDiproses()) {
            return back()->with('error', 'Hanya pesanan berstatus Belum Dibayar yang bisa diproses.');
        }

        if ($aksi === 'lunas') {
            // Garansi terbit otomatis bersamaan dengan konfirmasi lunas
            DB::transaction(function () use ($transaksi, $idPegawai) {
                $transaksi->update([
                    'status_pembayaran' => TransaksiPenjualan::LUNAS,
                    'id_pegawai'        => $idPegawai,
                ]);

                Garansi::terbitkanUntuk($transaksi);
            });

            return back()->with('sukses', 'Pembayaran pesanan #' . $transaksi->id_transaksi . ' dikonfirmasi lunas dan garansi diterbitkan.');
        }

        $transaksi->batalkan($idPegawai);

        return back()->with('sukses', 'Pesanan #' . $transaksi->id_transaksi . ' dibatalkan dan stok dikembalikan.');
    }
}
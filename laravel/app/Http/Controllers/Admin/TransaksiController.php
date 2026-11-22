<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garansi;
use App\Models\Pengiriman;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $transaksi = TransaksiPenjualan::with(['pelanggan', 'pengiriman'])
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
        $transaksi->load(['pelanggan', 'pegawai', 'detail.produk', 'pengiriman']);

        return view('admin.transaksi.show', compact('transaksi'));
    }

    /** Konfirmasi pembayaran ('lunas'), batalkan pesanan ('batal'), atau serahkan ke kurir ('kirim'). */
    public function update(Request $request, TransaksiPenjualan $transaksi)
    {
        $aksi = $request->validate([
            'aksi' => ['required', Rule::in(['lunas', 'batal', 'kirim'])],
        ])['aksi'];

        $idPegawai = auth('pegawai')->id();

        if ($aksi === 'kirim') {
            return $this->kirim($request, $transaksi);
        }

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

                // Pesanan yang dikirim masuk tahap dikemas setelah lunas
                $transaksi->pengiriman?->update(['status_pengiriman' => Pengiriman::DIKEMAS]);
            });

            return back()->with('sukses', 'Pembayaran pesanan #' . $transaksi->id_transaksi . ' dikonfirmasi lunas dan garansi diterbitkan.');
        }

        $transaksi->batalkan($idPegawai);

        return back()->with('sukses', 'Pesanan #' . $transaksi->id_transaksi . ' dibatalkan dan stok dikembalikan.');
    }

    /** Serahkan paket ke kurir: wajib mengisi nomor resi. */
    private function kirim(Request $request, TransaksiPenjualan $transaksi)
    {
        $pengiriman = $transaksi->pengiriman;

        if (! $pengiriman
            || $transaksi->status_pembayaran !== TransaksiPenjualan::LUNAS
            || $pengiriman->status_pengiriman !== Pengiriman::DIKEMAS) {
            return back()->with('error', 'Pesanan belum siap dikirim. Syaratnya: dikirim ke alamat, sudah lunas, dan berstatus Dikemas.');
        }

        $data = $request->validate([
            'no_resi' => ['required', 'string', 'max:40', 'regex:/^[A-Za-z0-9\-]+$/'],
        ], [
            'no_resi.required' => 'Nomor resi wajib diisi.',
            'no_resi.regex'    => 'Nomor resi hanya boleh berisi huruf, angka, dan tanda hubung.',
        ]);

        $pengiriman->update([
            'no_resi'           => strtoupper($data['no_resi']),
            'status_pengiriman' => Pengiriman::DIKIRIM,
            'tanggal_dikirim'   => now(),
        ]);

        return back()->with('sukses', 'Pesanan #' . $transaksi->id_transaksi . ' diserahkan ke ' . $pengiriman->kurir . ' dengan resi ' . $pengiriman->no_resi . '.');
    }
}
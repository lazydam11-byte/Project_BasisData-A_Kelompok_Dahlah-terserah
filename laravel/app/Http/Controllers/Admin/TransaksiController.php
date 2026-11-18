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
                    'status_pengiriman' => TransaksiPenjualan::KIRIM_DIKEMAS,   // ← BARU
                    'id_pegawai'        => $idPegawai,
                ]);

                Garansi::terbitkanUntuk($transaksi);
            });

            return back()->with('sukses', 'Pembayaran pesanan #' . $transaksi->id_transaksi . ' dikonfirmasi lunas dan garansi diterbitkan.');
        }

        $transaksi->batalkan($idPegawai);

        return back()->with('sukses', 'Pesanan #' . $transaksi->id_transaksi . ' dibatalkan dan stok dikembalikan.');
    }

    /** Atur estimasi tiba, kurir, dan nomor resi; atau tandai pesanan sudah dikirim. */   // ← BARU (seluruh method)
    public function pengiriman(Request $request, TransaksiPenjualan $transaksi)
    {
        if (! $transaksi->bisaDiaturPengirimannya()) {
            return back()->with('error', 'Pengiriman hanya bisa diatur untuk pesanan yang sudah lunas dan belum diterima.');
        }

        $kirim = $request->input('aksi') === 'kirim';

        $data = $request->validate([
            'aksi'          => ['required', Rule::in(['simpan', 'kirim'])],
            'estimasi_tiba' => ['required', 'date', 'after_or_equal:' . $transaksi->tanggal->toDateString()],
            'kurir'         => [Rule::requiredIf($kirim), 'nullable', 'string', 'max:50'],
            'nomor_resi'    => [Rule::requiredIf($kirim), 'nullable', 'string', 'max:50'],
        ], [
            'estimasi_tiba.required'        => 'Isi perkiraan tanggal tiba.',
            'estimasi_tiba.after_or_equal'  => 'Estimasi tiba tidak boleh lebih awal dari tanggal pesanan.',
            'kurir.required'                => 'Isi nama kurir untuk menandai pesanan dikirim.',
            'nomor_resi.required'           => 'Isi nomor resi untuk menandai pesanan dikirim.',
        ]);

        $nilai = [
            'estimasi_tiba' => $data['estimasi_tiba'],
            'kurir'         => $data['kurir'] ?? $transaksi->kurir,
            'nomor_resi'    => $data['nomor_resi'] ?? $transaksi->nomor_resi,
        ];

        if ($data['aksi'] === 'kirim') {
            $nilai['status_pengiriman'] = TransaksiPenjualan::KIRIM_DIKIRIM;
            $nilai['tanggal_kirim']     = $transaksi->tanggal_kirim?->toDateString() ?? now()->toDateString();
        } elseif ($transaksi->status_pengiriman === TransaksiPenjualan::KIRIM_MENUNGGU) {
            $nilai['status_pengiriman'] = TransaksiPenjualan::KIRIM_DIKEMAS; // pesanan lunas sebelum fitur pengiriman ada
        }

        $transaksi->update($nilai);

        return back()->with('sukses', $data['aksi'] === 'kirim'
            ? 'Pesanan #' . $transaksi->id_transaksi . ' ditandai sudah dikirim.'
            : 'Estimasi pengiriman pesanan #' . $transaksi->id_transaksi . ' disimpan.');
    }
}
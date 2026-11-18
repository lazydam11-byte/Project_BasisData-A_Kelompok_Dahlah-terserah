<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KlaimGaransi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KlaimController extends Controller
{
    public function index(Request $request)
    {
        $klaim = KlaimGaransi::with('garansi.detail.produk', 'garansi.detail.transaksi.pelanggan')
            ->when($request->status, fn ($q, $s) => $q->where('status_klaim', $s))
            ->when($request->q, function ($query, $q) {
                $query->where(function ($w) use ($q) {
                    $w->whereHas('garansi', fn ($g) => $g->where('nomor_garansi', 'like', "%{$q}%"))
                      ->orWhereHas('garansi.detail.transaksi.pelanggan', fn ($p) => $p->where('nama', 'like', "%{$q}%"));
                });
            })
            ->orderByRaw("FIELD(status_klaim, 'Diajukan', 'Diproses', 'Selesai', 'Ditolak')")
            ->orderByDesc('tanggal_klaim')
            ->paginate(10)
            ->withQueryString();

        return view('admin.klaim.index', [
            'klaim'        => $klaim,
            'daftarStatus' => KlaimGaransi::SEMUA,
        ]);
    }

    public function show(KlaimGaransi $klaim)
    {
        $klaim->load('garansi.detail.produk', 'garansi.detail.transaksi.pelanggan', 'pegawai');

        return view('admin.klaim.show', compact('klaim'));
    }

    /** Proses, selesaikan, atau tolak klaim. */
    public function update(Request $request, KlaimGaransi $klaim)
    {
        $data = $request->validate([
            'aksi'       => ['required', Rule::in(['proses', 'selesai', 'tolak'])],
            'keterangan' => [
                Rule::requiredIf(in_array($request->input('aksi'), ['selesai', 'tolak'], true)),
                'nullable', 'string', 'max:1000',
            ],
        ], [
            'keterangan.required' => 'Isi keterangan penanganan untuk menyelesaikan atau menolak klaim.',
        ]);

        // Alur: Diajukan -> Diproses -> Selesai, dan Ditolak bisa dari Diajukan atau Diproses
        $diizinkan = [
            KlaimGaransi::DIAJUKAN => ['proses', 'tolak'],
            KlaimGaransi::DIPROSES => ['selesai', 'tolak'],
        ];

        if (! in_array($data['aksi'], $diizinkan[$klaim->status_klaim] ?? [], true)) {
            return back()->with('error', 'Klaim yang sudah ' . strtolower($klaim->status_klaim) . ' tidak bisa diubah ke status tersebut.');
        }

        $statusBaru = [
            'proses'  => KlaimGaransi::DIPROSES,
            'selesai' => KlaimGaransi::SELESAI,
            'tolak'   => KlaimGaransi::DITOLAK,
        ][$data['aksi']];

        $selesai = in_array($data['aksi'], ['selesai', 'tolak'], true);

        $klaim->update([
            'status_klaim'    => $statusBaru,
            'id_pegawai'      => auth('pegawai')->id(),
            'keterangan'      => $data['keterangan'] ?? $klaim->keterangan,
            'tanggal_selesai' => $selesai ? now()->toDateString() : null,
        ]);

        return redirect()->route('admin.klaim.show', $klaim)
            ->with('sukses', 'Klaim #' . $klaim->id_klaim . ' sekarang berstatus ' . $statusBaru . '.');
    }
}
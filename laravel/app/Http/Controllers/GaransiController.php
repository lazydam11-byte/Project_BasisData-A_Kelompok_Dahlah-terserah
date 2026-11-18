<?php

namespace App\Http\Controllers;

use App\Models\Garansi;
use App\Models\KlaimGaransi;
use Illuminate\Http\Request;

class GaransiController extends Controller
{
    /** Daftar garansi dan riwayat klaim milik pelanggan yang sedang login. */
    public function index()
    {
        $idPelanggan = auth('pelanggan')->id();

        $garansi = Garansi::milik($idPelanggan)
            ->with(['detail.produk', 'klaim'])
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('id_garansi')
            ->get();

        $riwayatKlaim = KlaimGaransi::whereHas('garansi', fn ($q) => $q->milik($idPelanggan))
            ->with('garansi.detail.produk')
            ->orderByDesc('tanggal_klaim')
            ->orderByDesc('id_klaim')
            ->get();

        return view('garansi.index', compact('garansi', 'riwayatKlaim'));
    }

    public function formKlaim(Garansi $garansi)
    {
        $this->pastikanMilikSendiri($garansi);

        if ($alasan = $this->alasanTidakBisaKlaim($garansi)) {
            return redirect()->route('garansi.index')->with('error', $alasan);
        }

        $garansi->load('detail.produk');

        return view('garansi.klaim', compact('garansi'));
    }

    public function ajukanKlaim(Request $request, Garansi $garansi)
    {
        $this->pastikanMilikSendiri($garansi);

        if ($alasan = $this->alasanTidakBisaKlaim($garansi)) {
            return redirect()->route('garansi.index')->with('error', $alasan);
        }

        $data = $request->validate([
            'keluhan'        => ['required', 'string', 'min:10', 'max:1000'],
            'kondisi_barang' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'keluhan.required'        => 'Jelaskan keluhan Anda.',
            'keluhan.min'             => 'Keluhan terlalu singkat. Jelaskan minimal 10 karakter.',
            'kondisi_barang.required' => 'Jelaskan kondisi barang saat ini.',
            'kondisi_barang.min'      => 'Kondisi barang terlalu singkat. Jelaskan minimal 5 karakter.',
        ]);

        KlaimGaransi::create([
            'id_garansi'     => $garansi->getKey(),
            'tanggal_klaim'  => now()->toDateString(),
            'keluhan'        => $data['keluhan'],
            'kondisi_barang' => $data['kondisi_barang'],
            'status_klaim'   => KlaimGaransi::DIAJUKAN,
        ]);

        return redirect()->route('garansi.index')
            ->with('sukses', 'Klaim garansi berhasil diajukan. Pegawai akan memeriksanya.');
    }

    private function pastikanMilikSendiri(Garansi $garansi): void
    {
        abort_unless(
            Garansi::milik(auth('pelanggan')->id())->whereKey($garansi->getKey())->exists(),
            404
        );
    }

    private function alasanTidakBisaKlaim(Garansi $garansi): ?string
    {
        if (! $garansi->aktif()) {
            return 'Masa garansi ini sudah berakhir, jadi klaim tidak bisa diajukan.';
        }

        if ($garansi->klaim()->whereIn('status_klaim', KlaimGaransi::BERJALAN)->exists()) {
            return 'Masih ada klaim yang sedang diproses untuk garansi ini.';
        }

        if ($garansi->jatahKlaimHabis()) {
            return 'Garansi ini sudah mencapai batas ' . config('garansi.maks_klaim') . ' kali klaim.';
        }

        return null;
    }

    
}
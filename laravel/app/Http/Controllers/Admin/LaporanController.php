<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaporanController extends Controller
{
    /** Halaman laporan penjualan (hanya transaksi berstatus Lunas yang dihitung). */
    public function penjualan(Request $request)
    {
        $filter    = $this->filter($request);
        $transaksi = $this->ambilTransaksi($filter);

        return view('admin.laporan.penjualan', [
            'filter'       => $filter,
            'transaksi'    => $transaksi,
            'ringkasan'    => $this->ringkasan($transaksi),
            'perMetode'    => $this->perMetode($transaksi),
            'terlaris'     => $this->terlaris($transaksi),
            'daftarMetode' => TransaksiPenjualan::METODE,
        ]);
    }

    /** Unduh laporan sebagai CSV (dibuka di Excel). */
    public function penjualanCsv(Request $request)
    {
        $filter    = $this->filter($request);
        $transaksi = $this->ambilTransaksi($filter);
        $ringkasan = $this->ringkasan($transaksi);

        $namaFile = 'laporan-penjualan-' . $filter['dari'] . '-sd-' . $filter['sampai'] . '.csv';

        return response()->streamDownload(function () use ($transaksi, $ringkasan, $filter) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM supaya Excel membaca UTF-8

            // Pemisah titik koma agar terbaca rapi di Excel dengan pengaturan Indonesia
            fputcsv($out, ['Laporan Penjualan Gadgetkuori'], ';');
            fputcsv($out, ['Periode', $filter['dari'] . ' s/d ' . $filter['sampai']], ';');
            fputcsv($out, ['Metode', $filter['metode'] ?? 'Semua'], ';');
            fwrite($out, "\n");

            fputcsv($out, ['No. Transaksi', 'Tanggal', 'Pelanggan', 'Metode', 'Unit', 'Total Produk (Rp)', 'Diproses oleh'], ';');

            foreach ($transaksi as $t) {
                fputcsv($out, [
                    $t->id_transaksi,
                    $t->tanggal->format('d/m/Y H:i'),
                    $this->aman($t->pelanggan->nama ?? '-'),
                    $t->metode_pembayaran,
                    $t->detail->sum('jumlah'),
                    $t->subtotal_produk,
                    $this->aman($t->pegawai->nama ?? '-'),
                ], ';');
            }

            fputcsv($out, ['', '', '', 'TOTAL', $ringkasan['unit_terjual'], $ringkasan['pendapatan'], ''], ';');
            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Ambil periode (default: awal bulan sampai hari ini) dan metode dari request. */
    private function filter(Request $request): array
    {
        $request->merge([
            'dari'   => $request->input('dari') ?: now()->startOfMonth()->toDateString(),
            'sampai' => $request->input('sampai') ?: now()->toDateString(),
        ]);

        $data = $request->validate([
            'dari'   => ['required', 'date'],
            'sampai' => ['required', 'date', 'after_or_equal:dari'],
            'metode' => ['nullable', Rule::in(TransaksiPenjualan::METODE)],
        ], [
            'sampai.after_or_equal' => 'Tanggal akhir tidak boleh lebih awal dari tanggal awal.',
        ]);

        return [
            'dari'   => $data['dari'],
            'sampai' => $data['sampai'],
            'metode' => $data['metode'] ?? null,
        ];
    }

    private function ambilTransaksi(array $filter)
    {
        return TransaksiPenjualan::with(['pelanggan', 'pegawai', 'detail.produk', 'pengiriman'])
            ->where('status_pembayaran', TransaksiPenjualan::LUNAS)
            ->whereDate('tanggal', '>=', $filter['dari'])
            ->whereDate('tanggal', '<=', $filter['sampai'])
            ->when($filter['metode'], fn ($q, $m) => $q->where('metode_pembayaran', $m))
            ->orderBy('tanggal')
            ->get();
    }

    private function ringkasan($transaksi): array
    {
        $jumlah     = $transaksi->count();
        $pendapatan = (int) $transaksi->sum('subtotal_produk');

        return [
            'jumlah_transaksi' => $jumlah,
            'pendapatan'       => $pendapatan,
            'unit_terjual'     => (int) $transaksi->sum(fn ($t) => $t->detail->sum('jumlah')),
            'rata_rata'        => $jumlah ? (int) round($pendapatan / $jumlah) : 0,
        ];
    }

    private function perMetode($transaksi)
    {
        return $transaksi->groupBy('metode_pembayaran')
            ->map(fn ($g) => ['jumlah' => $g->count(), 'total' => (int) $g->sum('subtotal_produk')])
            ->sortByDesc('total');
    }

    /** 5 produk dengan unit terjual terbanyak pada periode ini. */
    private function terlaris($transaksi)
    {
        return $transaksi->flatMap(fn ($t) => $t->detail)
            ->groupBy('id_produk')
            ->map(fn ($g) => [
                'nama'  => $g->first()->produk->nama_produk ?? 'Produk dihapus',
                'unit'  => (int) $g->sum('jumlah'),
                'omzet' => (int) $g->sum('subtotal'),
            ])
            ->sortByDesc('unit')
            ->take(5)
            ->values();
    }

    /** Cegah Excel menjalankan teks yang diawali = + - @ sebagai rumus. */
    private function aman(string $nilai): string
    {
        return preg_match('/^[=+\-@\t\r]/', $nilai) ? "'" . $nilai : $nilai;
    }
}
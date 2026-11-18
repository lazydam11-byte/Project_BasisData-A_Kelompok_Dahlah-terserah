<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Garansi extends Model
{
    protected $table = 'garansi';
    protected $primaryKey = 'id_garansi';

    protected $fillable = [
        'id_detail', 'nomor_garansi', 'tanggal_mulai',
        'lama_garansi', 'tanggal_berakhir', 'ketentuan_garansi',
    ];

    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_berakhir' => 'date',
    ];

    public function detail()
    {
        return $this->belongsTo(DetailPenjualan::class, 'id_detail', 'id_detail');
    }

    public function klaim()
    {
        return $this->hasMany(KlaimGaransi::class, 'id_garansi', 'id_garansi');
    }

    /** Garansi milik pelanggan tertentu (lewat detail -> transaksi). */
    public function scopeMilik(Builder $query, int $idPelanggan): Builder
    {
        return $query->whereHas('detail.transaksi', fn ($t) => $t->where('id_pelanggan', $idPelanggan));
    }

    /** Garansi masih berlaku sampai akhir hari tanggal_berakhir. */
    public function aktif(): bool
    {
        return now()->startOfDay()->lte($this->tanggal_berakhir);
    }

    public function sisaHari(): int
    {
        return max(0, (int) now()->startOfDay()->diffInDays($this->tanggal_berakhir, false));
    }

    /** Terbitkan garansi untuk setiap barang pada transaksi yang baru lunas. */
    public static function terbitkanUntuk(TransaksiPenjualan $transaksi): void
    {
        $transaksi->loadMissing('detail.produk');
        $mulai = now()->startOfDay();

        foreach ($transaksi->detail as $d) {
            $lama = self::lamaBulanDari($d->produk);

            if ($lama < 1) {
                continue; // produk tanpa garansi
            }

            self::firstOrCreate(
                ['id_detail' => $d->id_detail], // firstOrCreate: aman jika dipanggil dua kali
                [
                    'nomor_garansi'     => 'GR-' . $mulai->format('Ymd') . '-' . str_pad((string) $d->id_detail, 5, '0', STR_PAD_LEFT),
                    'tanggal_mulai'     => $mulai->toDateString(),
                    'lama_garansi'      => $lama,
                    'tanggal_berakhir'  => $mulai->copy()->addMonthsNoOverflow($lama)->toDateString(),
                    'ketentuan_garansi' => config('garansi.ketentuan'),
                ]
            );
        }
    }

    /**
     * Lama garansi produk dalam bulan.
     * Jika kolom garansi di tabel produk-mu bernama lain, cukup ubah daftar di bawah.
     */
    public static function lamaBulanDari(?Produk $produk): int
    {
        if (! $produk) {
            return 0;
        }

        foreach (['garansi_bulan', 'lama_garansi', 'garansi'] as $kolom) {
            $nilai = $produk->getAttribute($kolom);
            if (is_numeric($nilai)) {
                return (int) $nilai;
            }
        }

        // Cadangan: baca angka dari teks seperti "12 bulan" atau "1 tahun"
        $teks = (string) $produk->text_garansi;
        if (preg_match('/(\d+)/', $teks, $m)) {
            return stripos($teks, 'tahun') !== false ? (int) $m[1] * 12 : (int) $m[1];
        }

        return 0;
    }

        /** Jumlah klaim yang memakai jatah (klaim yang ditolak tidak dihitung). */
    public function klaimTerpakai(): int
    {
        return $this->klaim->where('status_klaim', '!=', KlaimGaransi::DITOLAK)->count();
    }

    public function jatahKlaimHabis(): bool
    {
        $maks = config('garansi.maks_klaim');

        return $maks !== null && $this->klaimTerpakai() >= $maks;
    }
}
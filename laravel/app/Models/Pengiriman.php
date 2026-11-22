<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    public const AMBIL_DI_TOKO = 'Ambil di Toko';
    public const KIRIM = 'Kirim ke Alamat';
    public const CARA = [self::AMBIL_DI_TOKO, self::KIRIM];

    public const MENUNGGU = 'Menunggu Pembayaran';
    public const DIKEMAS = 'Dikemas';
    public const DIKIRIM = 'Dikirim';
    public const DITERIMA = 'Diterima';
    public const DIBATALKAN = 'Dibatalkan';

    /** Urutan tahap yang ditampilkan pada pelacakan. */
    public const TAHAPAN = [self::MENUNGGU, self::DIKEMAS, self::DIKIRIM, self::DITERIMA];

    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';

    protected $fillable = [
        'id_transaksi', 'nama_penerima', 'telepon_penerima', 'alamat_tujuan', 'kurir',
        'ongkir', 'no_resi', 'status_pengiriman', 'tanggal_dikirim', 'tanggal_diterima',
    ];

    protected $casts = [
        'tanggal_dikirim'  => 'datetime',
        'tanggal_diterima' => 'datetime',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'id_transaksi', 'id_transaksi');
    }

    /** Daftar kurir dari config/pengiriman.php: nama => ['tarif' => int, 'estimasi' => string]. */
    public static function daftarKurir(): array
    {
        return config('pengiriman.kurir', []);
    }

    /** Posisi tahap saat ini pada TAHAPAN (false bila dibatalkan). */
    public function posisiTahap(): int|false
    {
        return array_search($this->status_pengiriman, self::TAHAPAN, true);
    }
}

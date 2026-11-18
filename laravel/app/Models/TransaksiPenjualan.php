<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransaksiPenjualan extends Model
{
    public const METODE = ['Transfer Bank', 'QRIS'];

    public const BELUM_DIBAYAR = 'Belum Dibayar';
    public const LUNAS = 'Lunas';
    public const DIBATALKAN = 'Dibatalkan';

    public const KIRIM_MENUNGGU = 'Menunggu';
    public const KIRIM_DIKEMAS  = 'Dikemas';
    public const KIRIM_DIKIRIM  = 'Dikirim';
    public const KIRIM_DITERIMA = 'Diterima';

    protected $table = 'transaksi_penjualan';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'tanggal', 'metode_pembayaran', 'status_pembayaran',
        'total_bayar', 'bukti_pembayaran', 'id_pelanggan', 'id_pegawai',
        'nama_penerima', 'telepon_penerima', 'alamat_pengiriman',
        'status_pengiriman', 'kurir', 'nomor_resi', 'tanggal_kirim', 'estimasi_tiba',
    ];

    protected $casts = [
        'tanggal'       => 'datetime',
        'tanggal_kirim' => 'date',
        'estimasi_tiba' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function detail()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_transaksi', 'id_transaksi');
    }

    /** URL foto bukti pembayaran (pakai asset() agar ikut host yang sedang dibuka). */
    public function getBuktiUrlAttribute(): ?string
    {
        return $this->bukti_pembayaran ? asset('storage/' . $this->bukti_pembayaran) : null;
    }

    /** Bukti pembayaran hanya relevan untuk pembayaran non-tunai. */
    public function bisaUnggahBukti(): bool
    {
        return $this->bisaDiproses() && $this->metode_pembayaran !== 'Bayar di Toko';
    }

    /** Pegawai boleh mengatur pengiriman jika pesanan sudah lunas dan belum diterima pelanggan. */
    public function bisaDiaturPengirimannya(): bool
    {
        return $this->status_pembayaran === self::LUNAS
            && $this->status_pengiriman !== self::KIRIM_DITERIMA;
    }

    /** Pelanggan boleh menandai diterima hanya saat pesanan berstatus Dikirim. */
    public function bisaDiterima(): bool
    {
        return $this->status_pengiriman === self::KIRIM_DIKIRIM;
    }

    /** Hanya pesanan yang belum dibayar yang masih bisa dikonfirmasi atau dibatalkan. */
    public function bisaDiproses(): bool
    {
        return $this->status_pembayaran === self::BELUM_DIBAYAR;
    }

    /** Batalkan pesanan dan kembalikan stok produk. */
    public function batalkan(?int $idPegawai = null): bool
    {
        return DB::transaction(function () use ($idPegawai) {
            $terkini = self::lockForUpdate()->find($this->getKey());

            if (! $terkini || ! $terkini->bisaDiproses()) {
                return false;
            }

            foreach ($terkini->detail as $d) {
                Produk::whereKey($d->id_produk)->increment('stok', $d->jumlah);
            }

            $terkini->update([
                'status_pembayaran' => self::DIBATALKAN,
                'id_pegawai'        => $idPegawai ?? $terkini->id_pegawai,
            ]);

            $this->refresh();

            return true;
        });
    }
}
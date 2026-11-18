<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransaksiPenjualan extends Model
{
    public const METODE = ['Transfer Bank', 'QRIS', 'Bayar di Toko'];

    public const BELUM_DIBAYAR = 'Belum Dibayar';
    public const LUNAS = 'Lunas';
    public const DIBATALKAN = 'Dibatalkan';

    protected $table = 'transaksi_penjualan';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'tanggal', 'metode_pembayaran', 'status_pembayaran',
        'total_bayar', 'bukti_pembayaran', 'id_pelanggan', 'id_pegawai',
    ];

    protected $casts = ['tanggal' => 'datetime'];

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
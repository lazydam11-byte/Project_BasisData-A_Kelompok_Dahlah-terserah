<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;         
class Pegawai extends Authenticatable
{
    public const JABATAN = ['Manajer', 'Kasir', 'Teknisi', 'Gudang'];

    /** Jabatan yang boleh tambah / ubah / hapus produk. */
    public const KELOLA_PRODUK = ['Manajer', 'Gudang'];

    public const KELOLA_PEGAWAI = ['Manajer'];

    public const KELOLA_PELANGGAN = ['Manajer', 'Kasir'];

    public const KELOLA_TRANSAKSI = ['Manajer', 'Kasir'];

    public const KELOLA_LAPORAN = ['Manajer'];

    public const KELOLA_KLAIM = ['Manajer', 'Teknisi'];

    public function bisaKelolaKlaim(): bool
    {
        return in_array($this->jabatan, self::KELOLA_KLAIM, true);
    }
    public function bisaLihatLaporan(): bool
    {
        return in_array($this->jabatan, self::KELOLA_LAPORAN, true);
    }

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $fillable = ['nama', 'jabatan', 'username', 'password'];
    protected $hidden = ['password', 'remember_token'];

    // Password otomatis di-hash saat disimpan (Laravel 10.14+).
    protected $casts = ['password' => 'hashed'];

    public function bisaKelolaProduk(): bool
    {
        return in_array($this->jabatan, self::KELOLA_PRODUK, true);
    }

    /** Jabatan yang boleh mengelola data pegawai. */

    public function bisaKelolaPegawai(): bool
    {
        return in_array($this->jabatan, self::KELOLA_PEGAWAI, true);
    }

        public function bisaKelolaPelanggan(): bool
    {
        return in_array($this->jabatan, self::KELOLA_PELANGGAN, true);
    }


    public function bisaKelolaTransaksi(): bool
    {
        return in_array($this->jabatan, self::KELOLA_TRANSAKSI, true);
    }
}

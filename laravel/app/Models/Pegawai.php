<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;         
class Pegawai extends Authenticatable
{
    public const JABATAN = ['Manajer', 'Kasir', 'Teknisi', 'Gudang'];

    /** Jabatan yang boleh tambah / ubah / hapus produk. */
    public const KELOLA_PRODUK = ['Manajer', 'Gudang'];

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
}

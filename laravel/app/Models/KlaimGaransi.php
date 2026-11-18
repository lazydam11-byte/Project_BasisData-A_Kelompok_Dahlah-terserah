<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlaimGaransi extends Model
{
    public const DIAJUKAN = 'Diajukan';
    public const DIPROSES = 'Diproses';
    public const SELESAI  = 'Selesai';
    public const DITOLAK  = 'Ditolak';

    public const SEMUA    = [self::DIAJUKAN, self::DIPROSES, self::SELESAI, self::DITOLAK];
    public const BERJALAN = [self::DIAJUKAN, self::DIPROSES];

    protected $table = 'klaim_garansi';
    protected $primaryKey = 'id_klaim';

    protected $fillable = [
        'id_garansi', 'id_pegawai', 'tanggal_klaim', 'tanggal_selesai',
        'keluhan', 'kondisi_barang', 'status_klaim', 'keterangan',
    ];

    protected $casts = [
        'tanggal_klaim'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function garansi()
    {
        return $this->belongsTo(Garansi::class, 'id_garansi', 'id_garansi');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function masihBerjalan(): bool
    {
        return in_array($this->status_klaim, self::BERJALAN, true);
    }
}
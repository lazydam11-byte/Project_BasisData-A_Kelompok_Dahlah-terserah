<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori', 'id_merek', 'nama_produk', 'harga',
        'stok', 'lama_garansi', 'tanggal_masuk', 'foto_produk',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
        'lama_garansi' => 'integer',
        'tanggal_masuk' => 'date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function merek(): BelongsTo
    {
        return $this->belongsTo(Merek::class, 'id_merek', 'id_merek');
    }

    public function scopeCari(Builder $query, ?string $kata): Builder
    {
        return $kata ? $query->where('nama_produk', 'like', "%{$kata}%") : $query;
    }

    /** PRD-001 */
    public function getKodeAttribute(): string
    {
        return 'PRD-' . str_pad((string) $this->id_produk, 3, '0', STR_PAD_LEFT);
    }

    /** Rp 14.500.000 */
    public function getHargaRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_produk ? asset('storage/' . $this->foto_produk) : null;
    }

    public function getTextGaransiAttribute(): string
    {
        return $this->lama_garansi > 0 ? $this->lama_garansi . ' bulan' : 'Tanpa garansi';
    }

    /** [label, kelas warna] */
    public function getStatusStokAttribute(): array
    {
        if ($this->stok <= 0) {
            return ['Stok habis', 'bg-red-100 text-red-700'];
        }
        if ($this->stok <= 3) {
            return ['Stok terbatas · ' . $this->stok . ' unit', 'bg-amber-100 text-amber-800'];
        }
        return ['Stok tersedia · ' . $this->stok . ' unit', 'bg-green-100 text-green-800'];
    }
}


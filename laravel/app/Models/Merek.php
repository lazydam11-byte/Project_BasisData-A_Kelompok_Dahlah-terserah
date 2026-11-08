<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merek extends Model
{
    protected $table = 'merek';
    protected $primaryKey = 'id_merek';
    protected $fillable = ['nama_merek', 'negara_asal'];

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'id_merek', 'id_merek');
    }

    /** Cari berdasarkan nama merek atau negara asal. */
    public function scopeCari(Builder $query, ?string $kata): Builder
    {
        return $kata
            ? $query->where(fn($q) => $q->where('nama_merek', 'like', "%{$kata}%")
                ->orWhere('negara_asal', 'like', "%{$kata}%"))
            : $query;
    }

    /** MRK-001 */
    public function getKodeAttribute(): string
    {
        return 'MRK-' . str_pad((string) $this->id_merek, 3, '0', STR_PAD_LEFT);
    }
}

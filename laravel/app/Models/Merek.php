<?php

namespace App\Models;

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
}
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Authenticatable
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = ['nama', 'alamat', 'no_telepon', 'email', 'username', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['password' => 'hashed'];
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->string('nama', 100);
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            // Akun login pembeli. Nullable: pelanggan yang dicatat pegawai di toko boleh belum punya akun.
            $table->string('username', 50)->nullable()->unique();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};

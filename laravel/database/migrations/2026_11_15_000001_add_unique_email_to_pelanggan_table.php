<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Email dipakai sebagai identitas login pelanggan, jadi harus unik.
     * Kolom tetap nullable: pelanggan yang dicatat pegawai di toko boleh tanpa email.
     */
    public function up(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->unique('email');
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });
    }
};

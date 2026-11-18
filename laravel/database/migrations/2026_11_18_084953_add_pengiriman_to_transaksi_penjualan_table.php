<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            // Diisi pelanggan saat checkout
            $table->string('nama_penerima', 100)->nullable();
            $table->string('telepon_penerima', 20)->nullable();
            $table->text('alamat_pengiriman')->nullable();

            // Diisi pegawai setelah pesanan lunas
            $table->string('status_pengiriman', 20)->default('Menunggu'); // Menunggu, Dikemas, Dikirim, Diterima
            $table->string('kurir', 50)->nullable();
            $table->string('nomor_resi', 50)->nullable();
            $table->date('tanggal_kirim')->nullable();
            $table->date('estimasi_tiba')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->dropColumn([
                'nama_penerima', 'telepon_penerima', 'alamat_pengiriman',
                'status_pengiriman', 'kurir', 'nomor_resi', 'tanggal_kirim', 'estimasi_tiba',
            ]);
        });
    }
};
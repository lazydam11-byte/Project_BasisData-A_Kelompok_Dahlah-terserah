<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_penjualan', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->dateTime('tanggal');
            $table->string('metode_pembayaran', 30);
            $table->string('status_pembayaran', 30)->default('Belum Dibayar');
            $table->unsignedBigInteger('total_bayar')->default(0); // hasil jumlah seluruh subtotal
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan');
            // Kosong saat pesanan dibuat lewat web; terisi saat pegawai memproses
            $table->foreignId('id_pegawai')->nullable()->constrained('pegawai', 'id_pegawai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualan');
    }
};
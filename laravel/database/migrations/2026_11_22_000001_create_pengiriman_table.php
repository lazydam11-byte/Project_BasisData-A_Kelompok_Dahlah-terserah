<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Data pengiriman untuk pesanan online. Pesanan tanpa baris di tabel ini
     * berarti diambil sendiri di toko.
     */
    public function up(): void
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman');
            $table->foreignId('id_transaksi')->unique()
                ->constrained('transaksi_penjualan', 'id_transaksi')->cascadeOnDelete();
            $table->string('nama_penerima', 100);
            $table->string('telepon_penerima', 20);
            $table->text('alamat_tujuan');
            $table->string('kurir', 30);
            $table->unsignedBigInteger('ongkir')->default(0);
            $table->string('no_resi', 40)->nullable();           // diisi pegawai saat barang diserahkan ke kurir
            $table->string('status_pengiriman', 25)->default('Menunggu Pembayaran');
            $table->dateTime('tanggal_dikirim')->nullable();
            $table->dateTime('tanggal_diterima')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_transaksi')->constrained('transaksi_penjualan', 'id_transaksi')->cascadeOnDelete();
            $table->foreignId('id_produk')->constrained('produk', 'id_produk');
            $table->unsignedInteger('jumlah');
            $table->unsignedBigInteger('harga_satuan'); // harga saat dibeli, tidak ikut berubah jika harga produk diubah
            $table->unsignedBigInteger('subtotal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
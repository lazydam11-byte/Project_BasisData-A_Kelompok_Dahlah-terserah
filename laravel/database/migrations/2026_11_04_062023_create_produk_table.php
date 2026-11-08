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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori')->restrictOnDelete();
            $table->foreignId('id_merek')->constrained('merek', 'id_merek')->restrictOnDelete();
            $table->string('nama_produk', 150);
            $table->unsignedBigInteger('harga');                    // Rupiah, tanpa desimal
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedSmallInteger('lama_garansi')->default(0); // bulan, 0 = tanpa garansi
            $table->date('tanggal_masuk');
            $table->string('foto_produk')->nullable();               // path di storage/app/public
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};

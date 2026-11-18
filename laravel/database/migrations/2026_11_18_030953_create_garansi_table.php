<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garansi', function (Blueprint $table) {
            $table->id('id_garansi');
            // Satu baris detail penjualan punya satu garansi
            $table->foreignId('id_detail')->unique()->constrained('detail_penjualan', 'id_detail')->cascadeOnDelete();
            $table->string('nomor_garansi', 30)->unique();
            $table->date('tanggal_mulai');
            $table->unsignedSmallInteger('lama_garansi');   // dalam bulan
            $table->date('tanggal_berakhir');               // turunan: tanggal_mulai + lama_garansi
            $table->text('ketentuan_garansi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garansi');
    }
};
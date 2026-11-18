<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klaim_garansi', function (Blueprint $table) {
            $table->id('id_klaim');
            $table->foreignId('id_garansi')->constrained('garansi', 'id_garansi')->cascadeOnDelete();
            // Kosong saat pelanggan baru mengajukan; terisi saat pegawai memproses
            $table->foreignId('id_pegawai')->nullable()->constrained('pegawai', 'id_pegawai');
            $table->date('tanggal_klaim');
            $table->date('tanggal_selesai')->nullable();
            $table->text('keluhan');
            $table->text('kondisi_barang');
            $table->string('status_klaim', 20)->default('Diajukan');
            $table->text('keterangan')->nullable();         // keterangan penanganan dari pegawai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klaim_garansi');
    }
};
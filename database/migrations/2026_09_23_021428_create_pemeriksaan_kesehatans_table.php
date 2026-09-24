<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_kesehatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');

            // Bagian yang diisi oleh Petugas
            $table->string('tekanan_darah', 20)->nullable(); // TD
            $table->decimal('suhu_tubuh', 4, 1)->nullable();  // S (misal 36.5)
            $table->integer('nadi')->nullable();              // N
            $table->integer('spo2')->nullable();              // SpO2
            $table->string('petugas_pemeriksa')->nullable();  // Diisi oleh (Petugas)

            // Bagian Diverifikasi oleh Dokter
            $table->text('rekomendasi_dokter')->nullable();   // Rekomendasi Dokter
            $table->enum('status_kelayakan', ['Layak', 'Ditunda', 'Tidak Layak'])->nullable();
            $table->string('dokter_pemeriksa')->nullable();   // Diverifikasi oleh (Dokter)
            $table->timestamp('diverifikasi_pada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_kesehatans');
    }
};

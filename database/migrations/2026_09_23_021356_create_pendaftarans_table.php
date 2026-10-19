<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi', 50)->unique();
            
            // Data identitas yang diinput pemohon
            $table->string('nama_paspor');
            $table->string('nama_tambahan')->nullable();
            $table->string('alamat');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
           
            // Persetujuan Vaksinasi (Informed Consent)
            $table->string('persetujuan_untuk', 50)->default('Saya sendiri');
            $table->boolean('is_setuju')->default(true);
            $table->timestamp('tgl_persetujuan')->nullable();

            // 4 Berkas Lampiran
            $table->string('file_sinkarkes_terima');
            $table->string('file_sinkarkes_form');
            $table->string('file_paspor');
            $table->string('file_ktp')->nullable();

            // Jawaban kuesioner skrining disimpan fleksibel dalam bentuk JSON
            $table->json('data_skrining')->nullable();

            // Status alur berkas
            $table->enum('status_pendaftaran', ['Menunggu Verifikasi', 'Proses Medis', 'Selesai', 'Ditolak'])
                  ->default('Menunggu Verifikasi');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};

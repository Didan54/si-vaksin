<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            // 1. Tanda Vital
            $table->string('tekanan_darah', 20)->nullable()->after('status_pendaftaran');
            $table->string('suhu_tubuh', 10)->nullable()->after('tekanan_darah');
            $table->string('denyut_nadi', 10)->nullable()->after('suhu_tubuh');
            $table->string('spo2', 10)->nullable()->after('denyut_nadi');

            // 2. Petugas & Dokter
            $table->string('nama_petugas')->nullable()->after('spo2');
            $table->date('tgl_petugas')->nullable()->after('nama_petugas');
            $table->string('nama_dokter')->nullable()->after('tgl_petugas');
            $table->date('tgl_dokter')->nullable()->after('nama_dokter');

            // 3. Status Kelayakan Medis
            $table->string('status_kelayakan')->default('Layak Vaksin')->after('tgl_dokter');
            $table->text('catatan_dokter')->nullable()->after('status_kelayakan');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn([
                'tekanan_darah',
                'suhu_tubuh',
                'denyut_nadi',
                'spo2',
                'nama_petugas',
                'tgl_petugas',
                'nama_dokter',
                'tgl_dokter',
                'status_kelayakan',
                'catatan_dokter',
            ]);
        });
    }
};
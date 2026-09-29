<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            // Menambahkan kolom kartu_vaksin bertipe JSON/TEXT dan boleh kosong (nullable)
            $table->json('kartu_vaksin')->nullable()->after('file_ktp');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn('kartu_vaksin');
        });
    }
};
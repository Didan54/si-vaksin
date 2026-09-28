<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah kolom status_pendaftaran menjadi VARCHAR(50) bebas
        DB::statement("ALTER TABLE pendaftarans MODIFY COLUMN status_pendaftaran VARCHAR(50) NOT NULL DEFAULT 'Menunggu Verifikasi'");
    }

    public function down(): void
    {
        //
    }
};
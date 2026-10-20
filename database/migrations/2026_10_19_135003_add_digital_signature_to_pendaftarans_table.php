<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('signature_hash', 64)->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('kapasitas_pemohon')->default('Saya Sendiri');
            $table->boolean('setuju_anti_gratifikasi')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn([
                'signature_hash',
                'signed_at',
                'kapasitas_pemohon',
                'setuju_anti_gratifikasi',
            ]);
        });
    }
};
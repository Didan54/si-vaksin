<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->foreignId('vaksin_id')->nullable()->after('nomor_registrasi')->constrained('vaksins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropForeign(['vaksin_id']);
            $table->dropColumn('vaksin_id');
        });
    }
};

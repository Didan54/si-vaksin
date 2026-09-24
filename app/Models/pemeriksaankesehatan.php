<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanKesehatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'tekanan_darah',
        'suhu_tubuh',
        'nadi',
        'spo2',
        'petugas_pemeriksa',
        'rekomendasi_dokter',
        'status_kelayakan',
        'dokter_pemeriksa',
        'diverifikasi_pada',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
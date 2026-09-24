<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_registrasi',
        'vaksin_id',
        'nama_paspor',
        'nama_tambahan',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'file_sinkarkes_terima',
        'file_sinkarkes_form',
        'file_paspor',
        'file_ktp',
        'data_skrining',
        'status_pendaftaran',
    ];

    protected $casts = [
        'data_skrining' => 'array',
        'tanggal_lahir' => 'date',
    ];

    public function pemeriksaanKesehatan()
    {
        return $this->hasOne(PemeriksaanKesehatan::class);
    }
}
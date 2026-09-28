<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // <- 1. PASTIKAN INI DITAMBAHKAN DI ATAS

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_registrasi',
        'nama_paspor',
        'nama_tambahan',
        'nik',
        'no_paspor',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'tanggal_kunjungan',
        'file_sinkarkes_terima',
        'file_sinkarkes_form',
        'file_paspor',
        'file_ktp',
        'kartu_vaksin',
        'data_skrining',
        'status_pendaftaran',
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
    ];

    protected $casts = [
        'kartu_vaksin'  => 'array',
        'data_skrining' => 'array',
    ];

    public function vaksins()
    {
        return $this->belongsToMany(Vaksin::class, 'pendaftaran_vaksin');
    }

    // 2. TAMBAHKAN KODE DI BAWAH INI:
    // Otomatis bersihkan 4 file fisik dari folder storage saat data dihapus
    protected static function booted(): void
    {
        static::deleting(function (Pendaftaran $pendaftaran) {
            $berkas = [
                $pendaftaran->file_sinkarkes_terima,
                $pendaftaran->file_sinkarkes_form,
                $pendaftaran->file_paspor,
                $pendaftaran->file_ktp,
            ];

            foreach ($berkas as $file) {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        });
    }
}
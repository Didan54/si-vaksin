<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_registrasi',
        'nama_paspor',
        'nama_tambahan',
        'no_paspor',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'tanggal_kunjungan',
        'file_sinkarkes_terima',
        'file_sinkarkes_form',
        'file_paspor',
        'file_ktp',
        'kartu_vaksin',
        'data_skrining',
        'persetujuan_untuk',
        'is_setuju',
        'tgl_persetujuan',
        'status_pendaftaran',
        'signature_hash',
        'signed_at',
        'kapasitas_pemohon',
        'setuju_anti_gratifikasi',
        // Parameter pemeriksaan medis:
        'tekanan_darah',
        'suhu_tubuh',
        'denyut_nadi',
        'spo2',
        'stok_vaksin',
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

    /**
     * Hapus seluruh berkas fisik lampiran pemohon dari disk storage.
     */
    public function hapusBerkasFisik(): void
    {
        $berkas = [
            $this->file_sinkarkes_terima,
            $this->file_sinkarkes_form,
            $this->file_paspor,
            $this->file_ktp,
        ];

        foreach ($berkas as $file) {
            if (! $file) {
                continue;
            }

            // Normalisasi nama file agar aman dari slash '/' maupun backslash '\'
            $namaFile = basename($file);
            $jalurLengkap = 'lampiran_berkas/' . $namaFile;

            // 1. Cek & hapus di folder lampiran_berkas/ pada disk local (storage/app/lampiran_berkas/...)
            if (Storage::disk('local')->exists($jalurLengkap)) {
                Storage::disk('local')->delete($jalurLengkap);
            } elseif (Storage::disk('local')->exists($file)) {
                Storage::disk('local')->delete($file);
            }

            // 2. Fallback disk public (jika ada file dari implementasi lama)
            if (Storage::disk('public')->exists($jalurLengkap)) {
                Storage::disk('public')->delete($jalurLengkap);
            } elseif (Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }
    }

    /**
     * Lifecycle boot model Eloquent.
     */
    protected static function booted(): void
    {
        static::deleting(function (Pendaftaran $pendaftaran) {
            $pendaftaran->hapusBerkasFisik();
        });
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Vaksin; // 1. DITAMBAHKAN: Untuk memanggil data vaksin
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // 2. DITAMBAHKAN: Untuk validasi status aktif vaksin

class PendaftaranController extends Controller
{
    public function create()
    {
        // 3. DIUBAH: Mengambil semua jenis vaksin untuk ditampilkan di form
        $daftarVaksin = Vaksin::all();

        return view('pendaftaran.create', compact('daftarVaksin'));
    }

    public function store(Request $request)
    {
        // 4. DIUBAH: Menambahkan aturan validasi untuk pilihan vaksin
        $validated = $request->validate([
            'vaksin_id' => [
                'required',
                // Memastikan vaksin yang dipilih ada di tabel dan sedang diaktifkan admin
                Rule::exists('vaksins', 'id')->where(function ($query) {
                    $query->where('is_aktif', true);
                }),
            ],
            'nama_paspor'           => 'required|string|max:150',
            'nama_tambahan'         => 'required|string|max:150',
            'tempat_lahir'          => 'required|string|max:100',
            'tanggal_lahir'         => 'required|date',
            'jenis_kelamin'         => 'required|in:L,P',
            'file_sinkarkes_terima' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1120',
            'file_sinkarkes_form'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:1120',
            'file_paspor'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:1120',
            'file_ktp'              => 'required|file|mimes:pdf,jpg,jpeg,png|max:1120',
            'skrining'              => 'required|array',
        ], [
            // Pesan resmi jika seseorang mencoba memilih vaksin yang sedang dinonaktifkan
            'vaksin_id.required' => 'Silakan pilih salah satu layanan vaksinasi.',
            'vaksin_id.exists'   => 'Layanan vaksin yang dipilih sedang ditutup sementara (menunggu alokasi distribusi).',
        ]);

        // Simpan 4 berkas ke storage/app/public/lampiran_berkas
        $berkas = [];
        foreach (['file_sinkarkes_terima', 'file_sinkarkes_form', 'file_paspor', 'file_ktp'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $berkas[$fileKey] = $request->file($fileKey)->store('lampiran_berkas', 'public');
            }
        }

        // Generate Nomor Registrasi Unik (Contoh: REG-20260924-XXXX)
        $noRegistrasi = 'REG-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // Simpan Data Pendaftaran
        $pendaftaran = Pendaftaran::create([
            'nomor_registrasi'      => $noRegistrasi,
            'vaksin_id'             => $validated['vaksin_id'], // 5. DITAMBAHKAN: Menyimpan ID vaksin pilihan
            'nama_paspor'           => $validated['nama_paspor'],
            'nama_tambahan'         => $validated['nama_tambahan'],
            'tempat_lahir'          => $validated['tempat_lahir'],
            'tanggal_lahir'         => $validated['tanggal_lahir'],
            'jenis_kelamin'         => $validated['jenis_kelamin'],
            'file_sinkarkes_terima' => $berkas['file_sinkarkes_terima'],
            'file_sinkarkes_form'   => $berkas['file_sinkarkes_form'],
            'file_paspor'           => $berkas['file_paspor'],
            'file_ktp'              => $berkas['file_ktp'],
            'data_skrining'         => $validated['skrining'],
            'status_pendaftaran'    => 'Menunggu Verifikasi',
        ]);

        return redirect()->route('pendaftaran.sukses', $pendaftaran->id)
                         ->with('success', 'Pendaftaran berhasil dikirim.');
    }

    public function sukses($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        return view('pendaftaran.sukses', compact('pendaftaran'));
    }
}
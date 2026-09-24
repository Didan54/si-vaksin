<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Vaksin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    public function create()
    {
        $daftarVaksin = Vaksin::all();
        return view('pendaftaran.create', compact('daftarVaksin'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Validasi: Wajib memilih minimal 1 checkbox, dan tiap pilihan harus aktif di database
            'vaksin_id'   => 'required|array|min:1',
            'vaksin_id.*' => [
                'required',
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
            'kartu_vaksin'          => 'nullable|array',
            'skrining'              => 'required|array',
        ], [
            'vaksin_id.required' => 'Silakan pilih minimal satu jenis vaksinasi yang ingin diajukan.',
            'vaksin_id.*.exists' => 'Salah satu jenis vaksin yang dipilih sedang ditutup sementara.',
        ]);

        // Upload berkas
        $berkas = [];
        foreach (['file_sinkarkes_terima', 'file_sinkarkes_form', 'file_paspor', 'file_ktp'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $berkas[$fileKey] = $request->file($fileKey)->store('lampiran_berkas', 'public');
            }
        }

        $noRegistrasi = 'REG-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // 1. Simpan Data Pendaftaran Utama
        $pendaftaran = Pendaftaran::create([
            'nomor_registrasi'      => $noRegistrasi,
            'nama_paspor'           => $validated['nama_paspor'],
            'nama_tambahan'         => $validated['nama_tambahan'],
            'tempat_lahir'          => $validated['tempat_lahir'],
            'tanggal_lahir'         => $validated['tanggal_lahir'],
            'jenis_kelamin'         => $validated['jenis_kelamin'],
            'file_sinkarkes_terima' => $berkas['file_sinkarkes_terima'],
            'file_sinkarkes_form'   => $berkas['file_sinkarkes_form'],
            'file_paspor'           => $berkas['file_paspor'],
            'file_ktp'              => $berkas['file_ktp'],
            'kartu_vaksin'          => $request->input('kartu_vaksin', []),
            'data_skrining'         => $validated['skrining'],
            'status_pendaftaran'    => 'Menunggu Verifikasi',
        ]);

        // 2. Hubungkan jenis-jenis vaksin yang dipilih ke tabel pivot
        $pendaftaran->vaksins()->attach($validated['vaksin_id']);

        return redirect()->route('pendaftaran.sukses', $pendaftaran->id)
                         ->with('success', 'Pendaftaran berhasil dikirim.');
    }

    public function sukses($id)
    {
        $pendaftaran = Pendaftaran::with('vaksins')->findOrFail($id);
        return view('pendaftaran.sukses', compact('pendaftaran'));
    }
}
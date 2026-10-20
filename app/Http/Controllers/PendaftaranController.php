<?php

namespace App\Http\Controllers;

use App\Filament\Resources\PendaftaranResource;
use App\Models\Pendaftaran;
use App\Models\Vaksin;
use App\Models\HariLibur;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;

class PendaftaranController extends Controller
{
    public function create()
    {
        $daftarVaksin = Vaksin::all();
        $daftarLibur = HariLibur::pluck('tanggal')->toArray();

        return view('pendaftaran.create', compact('daftarVaksin', 'daftarLibur'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vaksin_id'   => 'required|array|min:1',
            'vaksin_id.*' => [
                'required',
                Rule::exists('vaksins', 'id')->where(function ($query) {
                    $query->where('is_aktif', true);
                }),
            ],
            'nama_paspor'           => 'required|string|max:150',
            'nama_tambahan'         => 'required|string|max:150',
            'no_paspor'             => 'required|string|max:25',
            'alamat'                => 'required|string',
            'tanggal_lahir'         => 'required|date',
            'jenis_kelamin'         => 'required|in:L,P',
            'tanggal_kunjungan'     => 'required|date',
            'file_sinkarkes_terima' => 'required|file|mimes:pdf|max:1024',
            'file_sinkarkes_form'   => 'required|file|mimes:pdf|max:1024',
            'file_paspor'           => 'required|file|mimes:jpeg,jpg,png|max:1024',
            'file_ktp'              => 'nullable|file|mimes:jpeg,jpg,png|max:1024',
            'kartu_vaksin'          => 'nullable|array',
            'skrining'              => 'required|array',
            'persetujuan_untuk'     => 'required|in:Saya sendiri,Anak saya,Orangtua saya',
            'is_setuju'             => 'accepted',
        ], [
            'vaksin_id.required'  => 'Silakan pilih minimal satu jenis vaksinasi yang ingin diajukan.',
            'vaksin_id.*.exists'  => 'Salah satu jenis vaksin yang dipilih sedang ditutup sementara.',
            'no_paspor.required'  => 'Nomor paspor wajib diisi.',
            'alamat.required'     => 'Alamat tempat tinggal wajib diisi.',
            'is_setuju.accepted'  => 'Anda wajib menyetujui surat pernyataan untuk melanjutkan pendaftaran.',
        ]);

        // Upload berkas
        $berkas = [];
        foreach (['file_sinkarkes_terima', 'file_sinkarkes_form', 'file_paspor', 'file_ktp'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $berkas[$fileKey] = $request->file($fileKey)->store('lampiran_berkas');
            } else {
                $berkas[$fileKey] = null;
            }
        }

        // 1. Waktu submit WIT (Jayapura/Sorong) & Nomor Registrasi
        $waktuPersetujuan = Carbon::now('Asia/Jayapura');
        $noRegistrasi = 'REG-' . $waktuPersetujuan->format('Ymd') . '-' . strtoupper(Str::random(4));

        // 2. Pembuatan Nilai Hash Integritas Dokumen (SHA-256)
        $payloadHash = $noRegistrasi . '|' . strtoupper($validated['nama_paspor']) . '|' . $waktuPersetujuan->toIso8601String();
        $signatureHash = hash('sha256', $payloadHash);

        // 3. Simpan Data Pendaftaran Utama
        $pendaftaran = Pendaftaran::create([
            'nomor_registrasi'        => $noRegistrasi,
            'nama_paspor'             => $validated['nama_paspor'],
            'nama_tambahan'           => $validated['nama_tambahan'],
            'no_paspor'               => $validated['no_paspor'],
            'alamat'                  => $validated['alamat'],
            'tanggal_lahir'           => $validated['tanggal_lahir'],
            'jenis_kelamin'           => $validated['jenis_kelamin'],
            'tanggal_kunjungan'       => $validated['tanggal_kunjungan'],
            'file_sinkarkes_terima'   => $berkas['file_sinkarkes_terima'],
            'file_sinkarkes_form'     => $berkas['file_sinkarkes_form'],
            'file_paspor'             => $berkas['file_paspor'],
            'file_ktp'                => $berkas['file_ktp'],
            'kartu_vaksin'            => $request->input('kartu_vaksin', []),
            'data_skrining'           => $validated['skrining'],
            'persetujuan_untuk'       => $validated['persetujuan_untuk'],
            'kapasitas_pemohon'       => $validated['persetujuan_untuk'], // Sinkronisasi dengan kolom baru
            'is_setuju'               => true,
            'setuju_anti_gratifikasi' => true,
            'signature_hash'          => $signatureHash,
            'signed_at'               => $waktuPersetujuan,
            'tgl_persetujuan'         => $waktuPersetujuan,
            'status_pendaftaran'      => 'Menunggu Verifikasi',
        ]);

        // 4. Hubungkan jenis-jenis vaksin yang dipilih ke tabel pivot
        $pendaftaran->vaksins()->attach($validated['vaksin_id']);

        // 5. Kirim notifikasi ke Admin Filament
        $admins = User::all();
        foreach ($admins as $admin) {
            Notification::make()
                ->title('Pendaftaran Vaksin Baru Masuk!')
                ->body('Pemohon: ' . $pendaftaran->nama_paspor . ' (' . $pendaftaran->nomor_registrasi . ')')
                ->success()
                ->icon('heroicon-o-bell-alert')
                ->actions([
                    NotificationAction::make('view')
                        ->button()
                        ->label('Lihat Detail')
                        ->url(PendaftaranResource::getUrl('index', isAbsolute: false))
                        ->markAsRead(),
                ])
                ->sendToDatabase($admin);
        }

        return redirect()->route('pendaftaran.create')->with('sukses_modal', [
            'nomor_registrasi' => $pendaftaran->nomor_registrasi,
            'nama_paspor'      => $pendaftaran->nama_paspor,
        ]);
    }

    /**
     * Layani akses dokumen privat pendaftaran untuk admin terotentikasi.
     */
    public function dokumenPrivat(string $path)
    {
        $cleanPath = ltrim(str_replace('lampiran_berkas/', '', $path), '/');
        $filePath = 'lampiran_berkas/' . $cleanPath;

        if (Storage::disk('local')->exists($filePath)) {
            return response()->file(storage_path('app/' . $filePath));
        }

        if (Storage::disk('local')->exists($path)) {
            return response()->file(storage_path('app/' . $path));
        }

        abort(404, 'Dokumen tidak ditemukan atau akses ditolak.');
    }

    /**
     * Tandai/hapus notifikasi terkait nomor registrasi dan redirect ke daftar pendaftaran.
     */
    public function bacaNotif(string $nomor_registrasi)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user) {
            $user->notifications()
                ->where('data', 'like', '%' . $nomor_registrasi . '%')
                ->delete();
        }

        return redirect('/admin/pendaftarans');
    }
}
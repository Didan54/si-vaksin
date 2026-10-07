<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PendaftaranPdfController extends Controller
{
    public function cetak($id)
    {
        $pendaftaran = Pendaftaran::with('vaksins')->findOrFail($id);

        // Ambil gambar base64 dengan mengecek disk private maupun public
        $pasporBase64 = $this->convertFileToBase64($pendaftaran->file_paspor);
        $ktpBase64    = $this->convertFileToBase64($pendaftaran->file_ktp);

        $pertanyaanSkrining = [
            1 => 'Apakah anda sedang sakit hari ini?',
            2 => 'Apakah anda memiliki alergi terhadap obat-obatan, makanan, komponen vaksin atau lateks?',
            3 => 'Apakah anda pernah mengalami reaksi alergi berat setelah menerima vaksinasi?',
            4 => 'Apakah anda memiliki penyakit kronis terkait jantung, paru-paru, asma, ginjal, penyakit metabolik (diabetes), anemia atau kelainan darah?',
            5 => 'Apakah anda menderita kanker, leukimia, HIV/AIDS atau gangguan daya tahan tubuh?',
            6 => 'Dalam 3 bulan terakhir, apakah anda mendapatkan terapi penekan imun (steroid/kemoterapi/radiasi)?',
            7 => 'Apakah anda pernah mengalami kejang atau gangguan sistem syaraf?',
            8 => 'Apakah anda menerima transfusi darah / terapi imunoglobulin / antiviral dalam 1 tahun terakhir?',
            9 => 'Apakah anda mendapatkan vaksinasi lain dalam 4 minggu terakhir?',
            10 => 'Apakah anda sedang hamil atau berencana hamil dalam 1 bulan ke depan? (Khusus Wanita)',
        ];

        $pdf = Pdf::loadView('admin.pdf.dokumen-pendaftaran', compact(
            'pendaftaran', 
            'pasporBase64', 
            'ktpBase64', 
            'pertanyaanSkrining'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream("Berkas-{$pendaftaran->nomor_registrasi}.pdf");
    }

    /**
     * Helper fleksibel membaca file baik di storage privat maupun publik
     */
    private function convertFileToBase64(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Jika format berkas adalah PDF, jangan ubah ke base64 image (tag img akan rusak)
        if ($extension === 'pdf') {
            return null;
        }

        // 1. Cek di disk default / private (storage/app/...)
        if (Storage::exists($path)) {
            $data = Storage::get($path);
            return 'data:image/' . $extension . ';base64,' . base64_encode($data);
        }

        // 2. Cek di disk public (storage/app/public/...) jika file lama
        if (Storage::disk('public')->exists($path)) {
            $data = Storage::disk('public')->get($path);
            return 'data:image/' . $extension . ';base64,' . base64_encode($data);
        }

        return null;
    }
}
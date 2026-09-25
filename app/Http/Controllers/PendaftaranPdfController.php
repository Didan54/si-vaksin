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

        // Helper untuk mengubah gambar storage ke base64 agar aman dirender oleh DomPDF di Windows
        $pasporBase64 = null;
        if ($pendaftaran->file_paspor && Storage::disk('public')->exists($pendaftaran->file_paspor)) {
            $type = pathinfo($pendaftaran->file_paspor, PATHINFO_EXTENSION);
            $data = Storage::disk('public')->get($pendaftaran->file_paspor);
            $pasporBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $ktpBase64 = null;
        if ($pendaftaran->file_ktp && Storage::disk('public')->exists($pendaftaran->file_ktp)) {
            $type = pathinfo($pendaftaran->file_ktp, PATHINFO_EXTENSION);
            $data = Storage::disk('public')->get($pendaftaran->file_ktp);
            $ktpBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

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
}
<?php

namespace App\Exports;

use App\Models\Pendaftaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PendaftaranExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $records;

    public function __construct($records = null)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records ?: Pendaftaran::with('vaksins')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No. Registrasi',
            'Tgl Daftar',
            'Rencana Kunjungan',
            'Nama Pemohon (Paspor)',
            'No. Paspor',
            'Tgl Lahir',
            'L/P',
            'Alamat Domisili',
            'Jenis Vaksin',
            'No. Stok / Buku',
        ];
    }

    public function map($pendaftaran): array
    {
        $namaLengkap = $pendaftaran->nama_paspor . ($pendaftaran->nama_tambahan ? " ({$pendaftaran->nama_tambahan})" : '');
        $daftarVaksin = $pendaftaran->vaksins->pluck('nama_vaksin')->implode(', ');

        return [
            $pendaftaran->nomor_registrasi,
            $pendaftaran->created_at ? $pendaftaran->created_at->format('d/m/Y') : '-',
            $pendaftaran->tanggal_kunjungan ? \Carbon\Carbon::parse($pendaftaran->tanggal_kunjungan)->format('d/m/Y') : '-',
            $namaLengkap,
            $pendaftaran->no_paspor ?? '-',
            $pendaftaran->tanggal_lahir ? \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->format('d/m/Y') : '-',
            $pendaftaran->jenis_kelamin ?? '-',
            $pendaftaran->alamat ?? '-',
            $daftarVaksin ?: '-',
            $pendaftaran->stok_vaksin ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $fullRange = "A1:{$highestColumn}{$highestRow}";

        // 1. Format Header Baris 1: Hijau BKK, Teks Putih Tebal, Rata Tengah
        $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D9488'], // Warna Teal Kemenkes
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);

        // 2. Beri garis (border) tipis ke seluruh tabel
        $sheet->getStyle($fullRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 3. Kolom tanggal, no registrasi, paspor, L/P, stok dibuat rata tengah
        $sheet->getStyle("A2:C{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E2:G{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("J2:K{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("M2:M{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
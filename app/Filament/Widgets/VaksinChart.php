<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VaksinChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Permohonan Vaksin';
    protected static ?int $sort = 3;

    // Pasang lebar 1 kolom agar pas berdampingan dengan tabel
    protected int | string | array $columnSpan = 1;

    // Ketinggian chart yang proporsional
    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        // Hitung peminat per jenis vaksin secara dinamis
        $dataVaksin = DB::table('vaksins')
            ->leftJoin('pendaftaran_vaksin', 'vaksins.id', '=', 'pendaftaran_vaksin.vaksin_id')
            ->select('vaksins.nama_vaksin', DB::raw('count(pendaftaran_vaksin.pendaftaran_id) as total'))
            ->groupBy('vaksins.id', 'vaksins.nama_vaksin')
            ->get();

        $labels = $dataVaksin->pluck('nama_vaksin')->toArray();
        $counts = $dataVaksin->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pemohon',
                    'data'  => $counts,
                    'backgroundColor' => [
                        '#10b981', // Hijau Emerald
                        '#0ea5e9', // Biru Sky
                        '#f59e0b', // Kuning Amber
                        '#8b5cf6', // Ungu
                        '#ec4899', // Pink
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    // MEMATIKAN GARIS-GARIS SKALA AGAR GRAFIK DONAT BERSIH
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom', // Keterangan warna vaksin muncul rapi di bawah donat
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false, // Hilangkan sumbu X
                ],
                'y' => [
                    'display' => false, // Hilangkan garis-garis & angka 0.1, 0.2 di sumbu Y
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
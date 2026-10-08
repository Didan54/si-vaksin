<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '5s';

    protected function getStats(): array
    {
        $hariIni = Carbon::today();

        $totalDaftar        = Pendaftaran::count();
        $menungguVerifikasi = Pendaftaran::where('status_pendaftaran', 'Menunggu Verifikasi')->count();
        $disetujui          = Pendaftaran::where('status_pendaftaran', 'Disetujui')->count();
        $jadwalHariIni      = Pendaftaran::whereDate('tanggal_kunjungan', $hariIni)->count();

        return [
            Stat::make('Total Pendaftar', $totalDaftar . ' Orang') // Diberi spasi
                ->description('Total permohonan masuk')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart([3, 5, 8, 4, 7, $totalDaftar]),

            Stat::make('Menunggu Verifikasi', $menungguVerifikasi . ' Berkas')
                ->description('Perlu tindakan di loket')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Kunjungan Hari Ini', $jadwalHariIni . ' Pasien')
                ->description('Jadwal pelayanan hari ini')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Berkas Disetujui', $disetujui . ' Pemohon')
                ->description('Lolos verifikasi klinik')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
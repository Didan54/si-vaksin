<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftaran;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class PendaftarTerbaruWidget extends BaseWidget
{
    protected static ?string $heading = 'Antrean Permohonan Masuk (Menunggu Tindakan)';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pendaftaran::query()
                    ->where('status_pendaftaran', 'Menunggu Verifikasi')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('nama_paspor')
                    ->label('Nama Pemohon')
                    ->weight('semibold'),

                TextColumn::make('tanggal_kunjungan')
                    ->label('Rencana Kunjungan')
                    ->date('d M Y')
                    ->badge()
                    ->color('info'),

                TextColumn::make('vaksins.nama_vaksin')
                    ->label('Layanan Vaksin')
                    ->badge()
                    ->color('success')
                    ->separator(', '),
            ])
            ->actions([
                Action::make('proses')
                    ->label('Buka Loket')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (): string => url('/admin/pendaftarans'))
                    ->button()
                    ->size('xs')
                    ->color('warning'),
            ])
            ->emptyStateHeading('Belum Ada Antrean Menunggu')
            ->emptyStateDescription('Semua berkas permohonan yang masuk saat ini sudah selesai diverifikasi.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated(false);
    }
}
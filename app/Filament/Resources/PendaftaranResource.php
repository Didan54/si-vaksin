<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Laporan Pendaftaran';
    protected static ?string $pluralModelLabel = 'Laporan Pendaftaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form detail pemohon
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                // 1. Nomor Urut
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),

                // Sisipan No. Registrasi
                TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                // 2. Tanggal Pendaftaran
                TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                // 3. Nama Paspor
                TextColumn::make('nama_paspor')
                    ->label('Nama Sesuai Paspor')
                    ->searchable()
                    ->sortable(),

                // 4. Nama Tambahan
                TextColumn::make('nama_tambahan')
                    ->label('Nama Tambahan')
                    ->searchable(),

                // 5. NIK
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->copyable()
                    ->color('gray'),

                // 6. No. Paspor
                TextColumn::make('no_paspor')
                    ->label('No. Paspor')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                // 7. Jenis Kelamin
                TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'L' => 'info',
                        'P' => 'warning',
                        default => 'gray',
                    }),

                // 8. Jenis Vaksin (Multiple Badge dari relasi pivot)
                TextColumn::make('vaksins.nama_vaksin')
                    ->label('Jenis Vaksinasi')
                    ->badge()
                    ->color('success')
                    ->separator(','),

                // 9. Status Pendaftaran
                TextColumn::make('status_pendaftaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Menunggu Verifikasi' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                // Filter dropdown untuk mempermudah petugas mencari berkas yang belum dicek
                SelectFilter::make('status_pendaftaran')
                    ->label('Filter Status')
                    ->options([
                        'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                    ]),
            ])
            ->actions([
                // 1. Aksi Verifikasi Kedatangan Pemohon di Klinik
                Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Pendaftaran $record): bool => $record->status_pendaftaran === 'Menunggu Verifikasi')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Berkas Pemohon')
                    ->modalDescription('Apakah pemohon sudah berada di klinik dan seluruh berkas fisik (Paspor asli & KTP) telah diperiksa serta dinyatakan valid?')
                    ->modalSubmitActionLabel('Ya, Sahkan & Verifikasi')
                    ->action(function (Pendaftaran $record) {
                        $record->update([
                            'status_pendaftaran' => 'Disetujui',
                        ]);

                        Notification::make()
                            ->title('Status Berhasil Diverifikasi')
                            ->body("Data pemohon {$record->nama_paspor} telah disetujui.")
                            ->success()
                            ->send();
                    }),

                // 2. Aksi Tolak (Jika berkas tidak sesuai saat dicek fisik)
                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Pendaftaran $record): bool => $record->status_pendaftaran === 'Menunggu Verifikasi')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pendaftaran')
                    ->modalDescription('Apakah berkas atau persyaratan fisik pemohon tidak sesuai kriteria?')
                    ->modalSubmitActionLabel('Tolak Berkas')
                    ->action(function (Pendaftaran $record) {
                        $record->update([
                            'status_pendaftaran' => 'Ditolak',
                        ]);

                        Notification::make()
                            ->title('Pendaftaran Ditolak')
                            ->danger()
                            ->send();
                    }),

                // 3. Tombol Pratinjau Dokumen PDF
                Action::make('cetak_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->url(fn (Pendaftaran $record): string => url("/admin/pendaftaran/{$record->id}/pdf"))
                    ->openUrlInNewTab(),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}
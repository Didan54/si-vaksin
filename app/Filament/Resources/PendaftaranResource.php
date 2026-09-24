<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

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
                // Form detail pemohon bawaan
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

                // 5. Jenis Kelamin
                TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'L' => 'info',
                        'P' => 'warning',
                        default => 'gray',
                    }),

                // 6. Jenis Vaksin (Multiple Badge dari relasi Many-to-Many)
                TextColumn::make('vaksins.nama_vaksin')
                    ->label('Jenis Vaksinasi')
                    ->badge()
                    ->color('success')
                    ->separator(','),

                // Status Pendaftaran
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
                //
            ])
            ->actions([
                // Tombol Aksi: Pratinjau Dokumen PDF
                Action::make('cetak_pdf')
                    ->label('Lihat Dokumen PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->url(fn (Pendaftaran $record): string => url("/admin/pendaftaran/{$record->id}/pdf"))
                    ->openUrlInNewTab(),

                Tables\Actions\ViewAction::make(),
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
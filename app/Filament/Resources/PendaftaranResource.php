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
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\HtmlString;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Laporan Pendaftaran';
    protected static ?string $pluralModelLabel = 'Laporan Pendaftaran';
    protected static ?int $navigationSort = 1;

    // 1. Kunci hak akses: Admin tidak diizinkan membuat data pendaftaran manual
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Skema form admin jika diperlukan
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                // Nomor Urut Baris
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),

                // No. Registrasi
                TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                // Tanggal Pendaftaran Dibuat
                TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                // Rencana Tanggal Kunjungan (Jadwal Booking Pemohon)
                TextColumn::make('tanggal_kunjungan')
                    ->label('Rencana Kunjungan')
                    ->date('d/m/Y')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                // Nama Sesuai Paspor
                TextColumn::make('nama_paspor')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable(),

                // NIK
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->copyable()
                    ->color('gray'),

                // Nomor Paspor
                TextColumn::make('no_paspor')
                    ->label('No. Paspor')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                // Jenis Kelamin
                TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'L' => 'info',
                        'P' => 'warning',
                        default => 'gray',
                    }),

                // Jenis Vaksin yang Diajukan
                TextColumn::make('vaksins.nama_vaksin')
                    ->label('Jenis Vaksin')
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
                SelectFilter::make('status_pendaftaran')
                    ->label('Filter Status')
                    ->options([
                        'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                    ]),
            ])
            ->actions([
                // SELURUH TINDAKAN DIBUNGKUS DALAM SATU TOMBOL DROPDOWN "AKSI"
                ActionGroup::make([
                    // 1. INPUT PEMERIKSAAN MEDIS & TANDA VITAL
                    Action::make('pemeriksaan_medis')
                        ->label('Pemeriksaan Fisik')
                        ->icon('heroicon-o-heart')
                        ->color('warning')
                        ->modalHeading(fn (Pendaftaran $record) => "Pemeriksaan Fisik & Dokter: {$record->nama_paspor}")
                        ->modalDescription('Input tanda-tanda vital pemohon dan validasi kelayakan vaksinasi:')
                        ->modalWidth('2xl')
                        ->fillForm(fn (Pendaftaran $record): array => [
                            'tekanan_darah'    => $record->tekanan_darah,
                            'suhu_tubuh'       => $record->suhu_tubuh,
                            'denyut_nadi'      => $record->denyut_nadi,
                            'spo2'             => $record->spo2,
                            'nama_petugas'     => $record->nama_petugas ?? auth()->user()->name,
                            'tgl_petugas'      => $record->tgl_petugas ?? now()->toDateString(),
                            'nama_dokter'      => $record->nama_dokter,
                            'tgl_dokter'       => $record->tgl_dokter ?? now()->toDateString(),
                            'status_kelayakan' => $record->status_kelayakan ?? 'Layak Vaksin',
                            'catatan_dokter'   => $record->catatan_dokter,
                        ])
                        ->form([
                            Forms\Components\Section::make('Tanda-Tanda Vital (Pemeriksaan Fisik)')
                                ->description('Hasil pengukuran fisik pemohon di loket penapisan')
                                ->schema([
                                    Forms\Components\TextInput::make('tekanan_darah')
                                        ->label('TD (Tekanan Darah)')
                                        ->placeholder('Contoh: 120/80')
                                        ->suffix('mmHg'),

                                    Forms\Components\TextInput::make('suhu_tubuh')
                                        ->label('S (Suhu Tubuh)')
                                        ->placeholder('Contoh: 36.5')
                                        ->suffix('°C'),

                                    Forms\Components\TextInput::make('denyut_nadi')
                                        ->label('N (Denyut Nadi)')
                                        ->placeholder('Contoh: 80')
                                        ->suffix('x/mnt'),

                                    Forms\Components\TextInput::make('spo2')
                                        ->label('SpO2')
                                        ->placeholder('Contoh: 98')
                                        ->suffix('%'),
                                ])->columns(4),

                            Forms\Components\Section::make('Verifikasi Petugas & Dokter')
                                ->schema([
                                    Forms\Components\TextInput::make('nama_petugas')
                                        ->label('Diisi Oleh (Petugas)')
                                        ->placeholder('Nama lengkap petugas skrining'),

                                    Forms\Components\DatePicker::make('tgl_petugas')
                                        ->label('Tanggal Petugas')
                                        ->displayFormat('d/m/Y'),

                                    Forms\Components\TextInput::make('nama_dokter')
                                        ->label('Diverifikasi Oleh (Dokter)')
                                        ->placeholder('Nama dokter pemeriksa'),

                                    Forms\Components\DatePicker::make('tgl_dokter')
                                        ->label('Tanggal Dokter')
                                        ->displayFormat('d/m/Y'),
                                ])->columns(2),

                            Forms\Components\Section::make('Kelayakan Vaksinasi')
                                ->schema([
                                    Forms\Components\Select::make('status_kelayakan')
                                        ->label('Kesimpulan / Rekomendasi Medis')
                                        ->options([
                                            'Layak Vaksin' => 'Layak Diberikan Vaksinasi',
                                            'Ditunda'      => 'Ditunda (Kondisi Medis Memerlukan Observasi)',
                                            'Tidak Layak'  => 'Tidak Layak Vaksin (Kontraindikasi Berat)',
                                        ])
                                        ->native(false)
                                        ->required(),

                                    Forms\Components\Textarea::make('catatan_dokter')
                                        ->label('Catatan Dokter / Keterangan Khusus')
                                        ->placeholder('Tuliskan catatan tambahan jika ada...')
                                        ->rows(2),
                                ]),
                        ])
                        ->action(function (Pendaftaran $record, array $data) {
                            $record->update($data);

                            Notification::make()
                                ->title('Data Pemeriksaan Disimpan')
                                ->body("Hasil pemeriksaan fisik untuk {$record->nama_paspor} berhasil diperbarui.")
                                ->success()
                                ->send();
                        }),

                    // 2. CEK 2 BERKAS SINKARKES
                    Action::make('cek_sinkarkes')
                        ->label('Berkas SINKARKES')
                        ->icon('heroicon-o-document-magnifying-glass')
                        ->color('info')
                        ->modalHeading(fn (Pendaftaran $record) => "Berkas SINKARKES: {$record->nama_paspor}")
                        ->modalDescription('Periksa kelengkapan tanda terima dan formulir pendaftaran dari Kemenkes:')
                        ->modalWidth('lg')
                        ->modalContent(fn (Pendaftaran $record) => new HtmlString('
                            <div style="display: flex; flex-direction: column; gap: 14px; margin-top: 10px;">
                                <!-- Berkas 1: Tanda Terima -->
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 700; font-size: 14px; color: #1e293b;">1. Tanda Terima Pendaftaran SINKARKES</div>
                                            <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Bukti registrasi resmi Kemenkes</div>
                                        </div>
                                        ' . ($record->file_sinkarkes_terima ? '
                                            <a href="' . asset('storage/' . $record->file_sinkarkes_terima) . '" target="_blank"
                                               style="display: inline-flex; align-items: center; gap: 6px; background: #0284c7; color: #ffffff; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
                                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                Buka File &nearr;
                                            </a>
                                        ' : '<span style="color: #ef4444; font-size: 12px; font-weight: 600;">Belum diunggah</span>') . '
                                    </div>
                                </div>

                                <!-- Berkas 2: Formulir Pendaftaran -->
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 700; font-size: 14px; color: #1e293b;">2. Formulir Pendaftaran SINKARKES</div>
                                            <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Formulir rincian data pemohon</div>
                                        </div>
                                        ' . ($record->file_sinkarkes_form ? '
                                            <a href="' . asset('storage/' . $record->file_sinkarkes_form) . '" target="_blank"
                                               style="display: inline-flex; align-items: center; gap: 6px; background: #0284c7; color: #ffffff; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
                                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                Buka File &nearr;
                                            </a>
                                        ' : '<span style="color: #ef4444; font-size: 12px; font-weight: 600;">Belum diunggah</span>') . '
                                    </div>
                                </div>
                            </div>
                        '))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup'),

                    // 3. CETAK PDF DOKUMEN
                    Action::make('cetak_pdf')
                        ->label('Cetak PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('gray')
                        ->url(fn (Pendaftaran $record): string => url("/admin/pendaftaran/{$record->id}/pdf"))
                        ->openUrlInNewTab(),

                    // 4. VERIFIKASI (Hanya jika masih menunggu)
                    Action::make('verifikasi')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Pendaftaran $record): bool => $record->status_pendaftaran === 'Menunggu Verifikasi')
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi Berkas Pemohon')
                        ->modalDescription('Pastikan pemohon sudah di loket, berkas fisik asli telah diperiksa, dan dokumen SINKARKES telah dicocokkan.')
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

                    // 5. TOLAK (Hanya jika masih menunggu)
                    Action::make('tolak')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Pendaftaran $record): bool => $record->status_pendaftaran === 'Menunggu Verifikasi')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Pendaftaran')
                        ->modalDescription('Tolak permohonan jika berkas fisik atau dokumen SINKARKES tidak valid.')
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

                    // 6. HAPUS DATA & BERKAS
                    DeleteAction::make()
                        ->label('Hapus Pendaftaran'),
                ])
                ->label('Aksi')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button(),
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
        ];
    }
}
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HariLiburResource\Pages;
use App\Models\HariLibur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HariLiburResource extends Resource
{
    protected static ?string $model = HariLibur::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Pengaturan Hari Libur';
    protected static ?string $pluralModelLabel = 'Daftar Hari Libur';
    protected static ?string $navigationGroup = 'Manajemen Sistem';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        // 1. Pemilihan Tanggal Libur
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Libur')
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->unique(ignoreRecord: true),
                        
                        // 2. Dropdown 4 Kategori Resmi
                        Forms\Components\Select::make('keterangan')
                            ->label('Kategori Hari Libur')
                            ->options([
                                'Libur Nasional' => 'Libur Nasional',
                                'Cuti Bersama' => 'Cuti Bersama',
                                'Libur Fakultatif / Daerah (Papua Barat Daya)' => 'Libur Fakultatif / Daerah (Papua Barat Daya)',
                                'Libur Khusus / Insidental' => 'Libur Khusus / Insidental (Kegiatan Kantor / Mendesak)',
                            ])
                            ->placeholder('-- Pilih Kategori Libur --')
                            ->native(false)
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal Libur')
                    ->date('d F Y')
                    ->sortable()
                    ->searchable(),
                
                // Menampilkan Kategori dengan Badge Warna
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Kategori Libur')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Libur Nasional' => 'danger',                                  // Merah
                        'Cuti Bersama' => 'warning',                                   // Kuning
                        'Libur Fakultatif / Daerah (Papua Barat Daya)' => 'info',      // Biru
                        'Libur Khusus / Insidental' => 'gray',                         // Abu-abu
                        default => 'primary',
                    })
                    ->searchable(),
            ])
            ->defaultSort('tanggal', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('keterangan')
                    ->label('Filter Kategori')
                    ->options([
                        'Libur Nasional' => 'Libur Nasional',
                        'Cuti Bersama' => 'Cuti Bersama',
                        'Libur Fakultatif / Daerah (Papua Barat Daya)' => 'Libur Fakultatif / Daerah',
                        'Libur Khusus / Insidental' => 'Libur Khusus / Insidental',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListHariLiburs::route('/'),
        ];
    }
}
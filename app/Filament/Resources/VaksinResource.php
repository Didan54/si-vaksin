<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VaksinResource\Pages;
use App\Models\Vaksin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class VaksinResource extends Resource
{
    protected static ?string $model = Vaksin::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Kelola Vaksin';
    protected static ?string $pluralModelLabel = 'Data Vaksin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_vaksin')
                    ->label('Nama Layanan Vaksin')
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_aktif')
                    ->label('Status Tersedia (Aktif)')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_vaksin')
                    ->label('Nama Layanan Vaksin')
                    ->searchable()
                    ->weight('bold'),

                // Sakelar on/off langsung di tabel
                ToggleColumn::make('is_aktif')
                    ->label('Ketersediaan Layanan')
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->color('gray'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListVaksins::route('/'),
            'create' => Pages\CreateVaksin::route('/create'),
            'edit' => Pages\EditVaksin::route('/{record}/edit'),
        ];
    }
}
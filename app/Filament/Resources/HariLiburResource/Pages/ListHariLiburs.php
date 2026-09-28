<?php

namespace App\Filament\Resources\HariLiburResource\Pages;

use App\Filament\Resources\HariLiburResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHariLiburs extends ListRecords
{
    protected static string $resource = HariLiburResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Hari Libur')
                ->icon('heroicon-o-plus-circle')
                ->modalHeading('Tambah Hari Libur Baru')
                ->modalWidth('2xl'), 
        ];
    }
}
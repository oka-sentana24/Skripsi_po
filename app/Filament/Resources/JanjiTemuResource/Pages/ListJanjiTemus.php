<?php

namespace App\Filament\Resources\JanjiTemuResource\Pages;

use App\Filament\Resources\JanjiTemuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJanjiTemus extends ListRecords
{
    protected static string $resource = JanjiTemuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Janji Temu')
                ->icon('heroicon-o-calendar')
                ->color('primary')
                ->tooltip('Tambah Janji Temu Baru'),
        ];
    }
}

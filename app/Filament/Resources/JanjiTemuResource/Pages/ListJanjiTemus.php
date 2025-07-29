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
            Actions\CreateAction::make(),
        ];
    }
}

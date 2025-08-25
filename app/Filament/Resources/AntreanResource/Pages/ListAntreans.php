<?php

namespace App\Filament\Resources\AntreanResource\Pages;

use App\Filament\Resources\AntreanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAntreans extends ListRecords
{
    protected static string $resource = AntreanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

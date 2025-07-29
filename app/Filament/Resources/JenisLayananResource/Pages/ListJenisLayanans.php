<?php

namespace App\Filament\Resources\JenisLayananResource\Pages;

use App\Filament\Resources\JenisLayananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisLayanans extends ListRecords
{
    protected static string $resource = JenisLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

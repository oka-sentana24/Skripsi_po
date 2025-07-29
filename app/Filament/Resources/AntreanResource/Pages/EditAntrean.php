<?php

namespace App\Filament\Resources\AntreanResource\Pages;

use App\Filament\Resources\AntreanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAntrean extends EditRecord
{
    protected static string $resource = AntreanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

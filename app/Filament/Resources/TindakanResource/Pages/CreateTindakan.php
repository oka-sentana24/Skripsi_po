<?php

namespace App\Filament\Resources\TindakanResource\Pages;

use App\Filament\Resources\TindakanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;


class CreateTindakan extends CreateRecord
{
    protected static string $resource = TindakanResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Tambah Pemeriksaan';
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('Tambah Pemeriksaan');
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah Pemeriksaan';
    }
}
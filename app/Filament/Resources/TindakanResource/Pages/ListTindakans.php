<?php

namespace App\Filament\Resources\TindakanResource\Pages;

use App\Filament\Resources\TindakanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;

class ListTindakans extends ListRecords
{
    protected static string $resource = TindakanResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Daftar Pemeriksaan';
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('Daftar Pemeriksaan');
    }

    /**
     * Breadcrumb khusus halaman ini
     */
    public function getBreadcrumb(): string
    {
        return 'Daftar Pemeriksaan';
    }

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
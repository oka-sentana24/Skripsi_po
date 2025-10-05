<?php

namespace App\Filament\Resources\PasienResource\Pages;

use App\Filament\Resources\PasienResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Exception;

class ListPasiens extends ListRecords
{
    protected static string $resource = PasienResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Daftar Pasien';
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('Daftar Pasien');
    }

    /**
     * Breadcrumb khusus halaman ini
     */
    public function getBreadcrumb(): string
    {
        return 'Daftar Pasien';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pasien')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->tooltip('Tambah Pasien Baru'),
        ];
    }
}

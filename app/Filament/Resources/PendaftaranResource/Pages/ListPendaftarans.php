<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ListPendaftarans extends ListRecords
{
    protected static string $resource = PendaftaranResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Daftar Registrasi';
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('Daftar Registrasi');
    }

    /**
     * Breadcrumb khusus halaman ini
     */
    public function getBreadcrumb(): string
    {
        return 'Daftar Registrasi';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Registrasi')
                ->icon('heroicon-o-plus'),
        ];
    }
}
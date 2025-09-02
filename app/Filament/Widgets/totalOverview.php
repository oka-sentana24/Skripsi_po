<?php

namespace App\Filament\Widgets;

use App\Models\JanjiTemu;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class totalOverview extends BaseWidget
{
    protected function getStats(): array
    {

        return [
            Stat::make(label: 'Jumlah Pasien', value: Pasien::count())
                ->description(description: 'Total pasien terdaftar')
                ->color('primary'), // bisa diganti sesuai tema

            // Stat::make(
            //     label: 'Janji Temu',
            //     value: JanjiTemu::whereDate('tanggal_janji', Carbon::today())->count()
            // )
            //     ->description('Jumlah janji temu hari ini')
            //     ->color('primary'),


            Stat::make(
                label: 'Registrasi',
                value: Pendaftaran::whereDate('pasien_id', Carbon::today())->count()
            )
                ->description('Jumlah registrasi hari ini')
                ->color('primary'),
        ];
    }
}

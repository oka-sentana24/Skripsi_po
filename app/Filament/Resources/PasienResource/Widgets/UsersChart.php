<?php

namespace App\Filament\Resources\PasienResource\Widgets;

use App\Models\Pasien;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $pasien = Pasien::select(
            DB::raw('COUNT(*) as total'),
            DB::raw('MONTH(created_at) as month')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        return [
            'datasets' => [
                [
                    'label' => 'Pasien',
                    'data' => $pasien->values(),
                ],
            ],
            'labels' => $pasien->keys()->map(fn($month) => date('F', mktime(0, 0, 0, $month, 1))),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

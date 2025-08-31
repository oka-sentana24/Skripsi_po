<?php

namespace App\Filament\Resources\PasienResource\Widgets;

use App\Models\Pasien;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PasiensChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Pasien Terdaftar';

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Per Hari',
            'week' => 'Per Minggu',
            'month' => 'Per Bulan',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? 'day'; // default per bulan

        if ($filter === 'day') {
            $pasien = Pasien::select(
                DB::raw('COUNT(*) as total'),
                DB::raw('DATE(created_at) as date')
            )
                ->whereYear('created_at', date('Y'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

            $labels = $pasien->keys()->map(fn($date) => date('d M', strtotime($date)));
        } elseif ($filter === 'week') {
            $pasien = Pasien::select(
                DB::raw('COUNT(*) as total'),
                DB::raw('WEEK(created_at) as week')
            )
                ->whereYear('created_at', date('Y'))
                ->groupBy('week')
                ->orderBy('week')
                ->pluck('total', 'week');

            $labels = $pasien->keys()->map(fn($week) => "Minggu $week");
        } else { // month
            $pasien = Pasien::select(
                DB::raw('COUNT(*) as total'),
                DB::raw('MONTH(created_at) as month')
            )
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $labels = $pasien->keys()->map(fn($month) => date('F', mktime(0, 0, 0, $month, 1)));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pasien',
                    'data' => $pasien->values(),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bisa diganti 'line' kalau mau
    }
}

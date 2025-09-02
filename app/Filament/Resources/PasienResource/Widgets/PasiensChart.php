<?php

namespace App\Filament\Resources\PasienResource\Widgets;

use App\Models\Pasien;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PasiensChart extends ChartWidget
{
    protected static ?string $heading = '📊 Jumlah Pasien Terdaftar';

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
        $filter = $this->filter ?? 'day'; // default per hari

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
                DB::raw('YEARWEEK(created_at, 1) as week')
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
                    'borderColor' => '#16a34a', // hijau elegan
                    'backgroundColor' => 'rgba(22, 163, 74, 0.2)',
                    'tension' => 0.4,
                    'fill' => true,
                    'pointBackgroundColor' => '#16a34a',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#15803d',
                    'pointHoverBorderColor' => '#fff',
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'labels' => [
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => '#1e293b',
                    'titleFont' => ['size' => 14, 'weight' => 'bold'],
                    'bodyFont' => ['size' => 13],
                    'padding' => 12,
                ],
            ],
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Periode',
                        'font' => ['size' => 14, 'weight' => 'bold'],
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1, // angka bulat
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Pasien',
                        'font' => ['size' => 14, 'weight' => 'bold'],
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // ganti ke line chart
    }
}

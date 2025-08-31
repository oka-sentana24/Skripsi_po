<?php

namespace App\Filament\Resources\JanjiTemuResource\Widgets;

use App\Models\JanjiTemu;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\ChartWidget;

class JanjiTemuChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Janji Temu per Status (Jam Kerja)';

    // Tambahkan property untuk tanggal
    public $startDate;
    public $endDate;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('dateRange')
                ->label('Pilih Tanggal')
                ->default([
                    'start' => now()->startOfDay(),
                    'end' => now()->endOfDay(),
                ])
                ->reactive()
                ->dehydrated(false)
                ->afterStateUpdated(function ($state, $set) {
                    $set('startDate', $state['start']);
                    $set('endDate', $state['end']);
                }),
        ];
    }

    protected function getData(): array
    {
        $start = $this->startDate ?? now()->startOfDay();
        $end = $this->endDate ?? now()->endOfDay();

        $statusOptions = [
            'dijadwalkan' => 'Dijadwalkan',
            'diproses'    => 'Diproses',
            'hadir'       => 'Hadir',
            'selesai'     => 'Selesai',
            'tidak_hadir' => 'Tidak Hadir',
            'dibatalkan'  => 'Dibatalkan',
        ];

        $rows = JanjiTemu::query()
            ->selectRaw("HOUR(jam_janji) as jam, status, COUNT(*) as total")
            ->whereBetween('tanggal_janji', [$start, $end])
            ->groupBy('jam', 'status')
            ->orderBy('jam')
            ->get();

        $labels = [];
        for ($h = 8; $h <= 16; $h++) {
            $labels[] = sprintf('%02d:00', $h);
        }

        $colors = [
            'dijadwalkan' => '#1f77b4',
            'diproses'    => '#ff7f0e',
            'hadir'       => '#2ca02c',
            'selesai'     => '#d62728',
            'tidak_hadir' => '#9467bd',
            'dibatalkan'  => '#8c564b',
        ];

        $datasets = [];
        foreach ($statusOptions as $key => $label) {
            $datasets[$key] = [
                'label' => $label,
                'data' => array_fill(0, count($labels), 0),
                'borderColor' => $colors[$key],
                'backgroundColor' => $colors[$key] . '33',
                'fill' => 'start',
                'tension' => 0.4,
            ];
        }

        foreach ($rows as $row) {
            $index = $row->jam - 8;
            if ($index >= 0 && $index < count($labels)) {
                $datasets[$row->status]['data'][$index] = $row->total;
            }
        }

        return [
            'labels' => $labels,
            'datasets' => array_values($datasets),
        ];
    }
}

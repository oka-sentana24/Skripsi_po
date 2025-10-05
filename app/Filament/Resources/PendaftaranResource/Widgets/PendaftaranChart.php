<?php

namespace App\Filament\Resources\PendaftaranResource\Widgets;

use App\Models\Pendaftaran;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PendaftaranChart extends ChartWidget
{
    protected static ?string $heading = '📈 Statistik Pendaftaran Pasien';

    public $status = null; // default semua status
    public $periode = 'day'; // default per hari
    public $start_date = null;
    public $end_date = null;

    protected function getFormSchema(): array
    {
        return [
            Select::make('status')
                ->label('Filter Status')
                ->options([
                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                    'terverifikasi' => 'Terverifikasi',
                    'diperiksa' => 'Diperiksa',
                    'selesai' => 'Selesai',
                    'batal' => 'Batal',
                ])
                ->placeholder('Semua Status')
                ->reactive(),

            Select::make('periode')
                ->label('Periode Waktu')
                ->options([
                    'day' => 'Per Hari',
                    'week' => 'Per Minggu',
                    'month' => 'Per Bulan',
                    'year' => "per Tahun"
                ])
                ->reactive(),

            DatePicker::make('start_date')
                ->label('Dari Tanggal')
                ->reactive(),

            DatePicker::make('end_date')
                ->label('Sampai Tanggal')
                ->reactive(),
        ];
    }

    protected function getData(): array
    {
        $query = Pendaftaran::query();

        // filter status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // filter tanggal history
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('tanggal_pendaftaran', [$this->start_date, $this->end_date]);
        } elseif ($this->start_date) {
            $query->whereDate('tanggal_pendaftaran', '>=', $this->start_date);
        } elseif ($this->end_date) {
            $query->whereDate('tanggal_pendaftaran', '<=', $this->end_date);
        } else {
            // default filter tahun ini
            $query->whereYear('tanggal_pendaftaran', date('Y'));
        }

        // generate data sesuai periode
        if ($this->periode === 'day') {
            $data = $query
                ->select(DB::raw('DATE(tanggal_pendaftaran) as date'), DB::raw('COUNT(*) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

            $labels = $data->keys()->map(fn($date) => date('d M', strtotime($date)));
        } elseif ($this->periode === 'week') {
            $data = $query
                ->select(DB::raw('YEARWEEK(tanggal_pendaftaran, 1) as week'), DB::raw('COUNT(*) as total'))
                ->groupBy('week')
                ->orderBy('week')
                ->pluck('total', 'week');

            $labels = $data->keys()->map(fn($week) => "Minggu $week");
        } else { // month
            $data = $query
                ->select(DB::raw('MONTH(tanggal_pendaftaran) as month'), DB::raw('COUNT(*) as total'))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $labels = $data->keys()->map(fn($month) => date('F', mktime(0, 0, 0, $month, 1)));
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Pendaftaran',
                    'data' => $data->values(),
                    'borderColor' => '#2563eb',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.2)',
                    'tension' => 0.4,
                    'fill' => true,
                    'pointBackgroundColor' => '#2563eb',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#1d4ed8',
                    'pointHoverBorderColor' => '#fff',
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                ],
            ],
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
                        'text' => 'Jumlah Pendaftaran',
                        'font' => ['size' => 14, 'weight' => 'bold'],
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

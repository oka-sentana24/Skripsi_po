<?php

namespace App\Filament\Resources\PendaftaranResource\Widgets;

use App\Models\Pendaftaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PendaftaranChart extends ChartWidget
{
    protected static ?string $heading = 'Pendaftaran';

    public $status = null; // default semua status
    public $periode = 'day'; // default per hari

    protected function getFormSchema(): array
    {
        return [
            Select::make('status')
                ->label('Status')
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
                ->label('Periode')
                ->options([
                    'day' => 'Per Hari',
                    'week' => 'Per Minggu',
                    'month' => 'Per Bulan',
                ])
                ->reactive(),
        ];
    }

    protected function getData(): array
    {
        $query = Pendaftaran::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->periode === 'day') {
            $data = $query
                ->select(DB::raw('DATE(tanggal_pendaftaran) as date'), DB::raw('COUNT(*) as total'))
                ->whereYear('tanggal_pendaftaran', date('Y'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

            $labels = $data->keys()->map(fn($date) => date('d M', strtotime($date)));
        } elseif ($this->periode === 'week') {
            $data = $query
                ->select(DB::raw('WEEK(tanggal_pendaftaran) as week'), DB::raw('COUNT(*) as total'))
                ->whereYear('tanggal_pendaftaran', date('Y'))
                ->groupBy('week')
                ->orderBy('week')
                ->pluck('total', 'week');

            $labels = $data->keys()->map(fn($week) => "Minggu $week");
        } else { // month
            $data = $query
                ->select(DB::raw('MONTH(tanggal_pendaftaran) as month'), DB::raw('COUNT(*) as total'))
                ->whereYear('tanggal_pendaftaran', date('Y'))
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
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

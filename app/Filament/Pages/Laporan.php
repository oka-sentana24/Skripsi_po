<?php

namespace App\Filament\Pages;

use App\Models\Pembayaran;
use App\Models\PenjualanProduk;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


use Illuminate\Support\Facades\Blade;
use Maatwebsite\Excel\Facades\Excel;           // <-- Tambahkan ini
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Support\Collection;

class Laporan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.laporan';

    public static function getNavigationSort(): ?int
    {
        return 6;
    }

    public $reportType;
    public $startDate;
    public $endDate;
    public $filter;
    public Collection $reportData;
    public array $headers = [];
    public ?string $selectedReportTitle = null;

    public function mount()
    {
        $this->form->fill([
            'reportType' => 'penjualan_produk',
            'startDate' => now()->startOfMonth()->toDateString(),
            'endDate' => now()->endOfMonth()->toDateString(),
            'filter' => null,
        ]);

        $this->reportData = collect([]);
        $this->generateReport();
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('reportType')
                ->label('Jenis Laporan')
                ->options([
                    'penjualan_produk'   => 'Penjualan Produk',
                    'pembayaran_layanan' => 'Pembayaran Layanan',
                    'kunjungan_pasien'   => 'Kunjungan Pasien',
                ])
                ->reactive(),

            DatePicker::make('startDate')->label('Tanggal Mulai')->required(),
            DatePicker::make('endDate')->label('Tanggal Akhir')->required(),

            TextInput::make('filter')
                ->label('Filter')
                ->placeholder('Cari nama produk/pasien/terapis...'),
        ];
    }

    public function generateReport()
    {
        $data = $this->form->getState();
        $reportType = $data['reportType'] ?? 'penjualan_produk';
        $start = Carbon::parse($data['startDate'])->startOfDay()->toDateTimeString();
        $end   = Carbon::parse($data['endDate'])->endOfDay()->toDateTimeString();
        $filter = $data['filter'] ?? null;

        switch ($reportType) {
            case 'penjualan_produk':
                $queryData = $this->getPenjualanProdukQuery($start, $end, $filter);
                $this->selectedReportTitle = 'Laporan Penjualan Produk';
                break;

            case 'pembayaran_layanan':
                $queryData = $this->getPembayaranLayananQuery($start, $end, $filter);
                $this->selectedReportTitle = 'Laporan Pembayaran Layanan';
                break;

            case 'kunjungan_pasien':
                $queryData = $this->getKunjunganPasienQuery($start, $end, $filter);
                $this->selectedReportTitle = 'Laporan Kunjungan Pasien';
                break;

            default:
                $queryData = ['data' => []];
        }

        $this->reportData = collect($queryData['data'] ?? []);
        $this->headers = $this->reportData->isNotEmpty()
            ? array_map(fn($k) => ucwords(str_replace('_', ' ', $k)), array_keys((array) $this->reportData->first()))
            : [];
    }

    // ========================== QUERY ==========================

    protected function getPenjualanProdukQuery($start, $end, $filter)
    {
        $query = PenjualanProduk::with(['produk', 'pendaftaran.pasien'])
            ->whereBetween('created_at', [$start, $end]);

        if (!empty($filter)) {
            $query->whereHas('produk', fn($q) => $q->where('nama', 'like', "%{$filter}%"))
                ->orWhereHas('pendaftaran.pasien', fn($q) => $q->where('nama', 'like', "%{$filter}%"));
        }

        $results = $query->latest()->get()->map(fn($item) => [
            'tanggal'     => $item->created_at?->format('Y-m-d') ?? '-',
            'nama_produk' => $item->produk->nama ?? '-',
            'jumlah'      => $item->jumlah,
            'subtotal'    => number_format($item->subtotal, 0, ',', '.'),
            'nama_pasien' => $item->pendaftaran->pasien->nama ?? '-',
        ]);

        return ['data' => $results->toArray()];
    }

    protected function getPembayaranLayananQuery($start, $end, $filter)
    {
        $query = Pembayaran::with(['pendaftaran.pasien', 'pendaftaran.tindakans.terapis', 'pendaftaran.tindakans.layanans'])
            ->whereBetween('created_at', [$start, $end]);

        if (!empty($filter)) {
            $query->whereHas('pendaftaran.pasien', fn($q) => $q->where('nama', 'like', "%{$filter}%"))
                ->orWhereHas('pendaftaran.tindakans.terapis', fn($q) => $q->where('nama', 'like', "%{$filter}%"))
                ->orWhereHas('pendaftaran.tindakans.layanans', fn($q) => $q->where('nama', 'like', "%{$filter}%"));
        }

        $results = $query->latest()->get()->flatMap(
            fn($pembayaran) =>
            $pembayaran->pendaftaran->tindakans->flatMap(
                fn($tindakan) =>
                $tindakan->layanans->map(fn($layanan) => [
                    'tanggal'     => $pembayaran->created_at?->format('Y-m-d') ?? '-',
                    'pasien'      => $pembayaran->pendaftaran->pasien->nama ?? '-',
                    'terapis'     => $tindakan->terapis->nama ?? '-',
                    'layanan'     => $layanan->nama ?? '-',
                    'harga'       => number_format($layanan->harga, 0, ',', '.'),
                    'total_bayar' => number_format($pembayaran->total_bayar, 0, ',', '.'),
                    'status'      => ucfirst($pembayaran->status),
                ])
            )
        );

        return ['data' => $results->toArray()];
    }

    protected function getKunjunganPasienQuery($start, $end, $filter)
    {
        $q = DB::table('pasiens as p')
            ->join('pendaftarans as pe', 'pe.pasien_id', '=', 'p.id')
            ->select(
                'p.id as pasien_id',
                'p.nama as nama_pasien',
                DB::raw('COUNT(pe.id) as jumlah_kunjungan'),
                DB::raw('MIN(pe.tanggal_pendaftaran) as kunjungan_pertama'),
                DB::raw('MAX(pe.tanggal_pendaftaran) as kunjungan_terakhir')
            )
            ->whereBetween('pe.tanggal_pendaftaran', [$start, $end])
            ->groupBy('p.id', 'p.nama')
            ->orderByDesc('jumlah_kunjungan');

        if (!empty($filter)) {
            $q->where('p.nama', 'like', "%{$filter}%");
        }

        $rows = $q->get()->map(fn($r) => [
            'nama_pasien'       => $r->nama_pasien,
            'jumlah_kunjungan'  => (int) $r->jumlah_kunjungan,
            'kunjungan_pertama' => $r->kunjungan_pertama ? Carbon::parse($r->kunjungan_pertama)->format('Y-m-d') : '-',
            'kunjungan_terakhir' => $r->kunjungan_terakhir ? Carbon::parse($r->kunjungan_terakhir)->format('Y-m-d') : '-',
        ]);

        return ['data' => $rows->toArray()];
    }

    protected function getReportDataForExport(): Collection
    {
        $data = $this->form->getState();
        $reportType = $data['reportType'] ?? 'penjualan_produk';
        $start = Carbon::parse($data['startDate'])->startOfDay()->toDateTimeString();
        $end = Carbon::parse($data['endDate'])->endOfDay()->toDateTimeString();
        $filter = $data['filter'] ?? null;

        // Panggil metode query yang sesuai
        switch ($reportType) {
            case 'penjualan_produk':
                $queryData = $this->getPenjualanProdukQuery($start, $end, $filter);
                break;
            case 'pembayaran_layanan':
                $queryData = $this->getPembayaranLayananQuery($start, $end, $filter);
                break;
            case 'kunjungan_pasien':
                $queryData = $this->getKunjunganPasienQuery($start, $end, $filter);
                break;
            default:
                $queryData = ['data' => []];
        }

        return collect($queryData['data'] ?? []);
    }

    /**
     * Method untuk ekspor ke file Excel.
     */
    public function exportToExcel()
    {
        $reportData = $this->getReportDataForExport();
        if ($reportData->isEmpty()) {
            $this->notify('error', 'Tidak ada data untuk diekspor.');
            return;
        }

        $headers = $this->reportData->isNotEmpty()
            ? array_map(fn($k) => ucwords(str_replace('_', ' ', $k)), array_keys((array) $this->reportData->first()))
            : [];

        $export = new class($reportData, $headers) implements FromCollection, WithHeadings {
            protected $data;
            protected $headers;

            public function __construct(Collection $data, array $headers)
            {
                $this->data = $data;
                $this->headers = $headers;
            }

            public function collection(): Collection
            {
                return $this->data->map(function ($row) {
                    $item = (array) $row;
                    foreach ($item as $key => $value) {
                        if (is_string($value) && str_contains($value, '.')) {
                            $item[$key] = (int) str_replace('.', '', $value);
                        }
                    }
                    return $item;
                });
            }

            public function headings(): array
            {
                return $this->headers;
            }
        };

        return Excel::download($export, $this->selectedReportTitle . '.xlsx');
    }

    /**
     * Method untuk ekspor ke file PDF.
     */
    public function exportToPdf()
    {
        $this->reportData = $this->getReportDataForExport();
        if ($this->reportData->isEmpty()) {
            $this->notify('error', 'Tidak ada data untuk diekspor.');
            return;
        }

        // Buat view khusus untuk PDF agar tampilannya rapi
        $html = Blade::render('pdfs.laporan', [
            'reportTitle' => $this->selectedReportTitle,
            'reportData' => $this->reportData,
            'headers' => $this->headers,
        ]);

        $pdf = Pdf::loadHtml($html);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $this->selectedReportTitle . '.pdf');
    }
}

<x-filament::page>
    <h1 class="text-xl font-bold mb-4">
        {{ $selectedReportTitle ?? 'Laporan' }}
    </h1>

    {{-- Form filter --}}
    <form wire:submit.prevent="generateReport" class="space-y-4">
        {{ $this->form }}
        <div>
            <x-filament::button type="submit" color="primary">
                Generate
            </x-filament::button>
        </div>
    </form>

    <div class="mt-6 rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
        @if ($reportData->isNotEmpty())
        <table class="w-full text-sm text-gray-700">
            <thead class="bg-gray-100"> {{-- Warna header yang lebih terang --}}
                <tr class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                    @foreach ($headers as $header)
                    <th class="px-4 py-3 text-left"> {{-- Tambahkan padding lebih untuk ruang --}}
                        {{ $header }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($reportData as $row)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    {{-- Tambahkan transisi hover yang lebih halus --}}
                    @foreach ($row as $value)
                    <td class="px-4 py-3 whitespace-nowrap text-gray-700"> {{-- Tambahkan padding lebih --}}
                        {{ $value ?? '-' }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        {{-- Empty state yang lebih modern --}}
        <div class="flex flex-col items-center justify-center p-8 text-center text-gray-500 bg-white min-h-[200px]"
            style="padding: 30px;">
            <div class="rounded-full bg-gray-100 p-3">
                {{-- Menggunakan komponen Blade UI Kit untuk Heroicon --}}
                @svg('heroicon-o-x-mark', 'h-8 w-8 text-gray-400')
            </div>

            {{-- Penambahan padding atas dan bawah di sini --}}
            <div class="pt-6 pb-4">
                <p class="text-sm font-medium text-gray-600">
                    Tidak ada pembayaran ditemukan.
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    Coba sesuaikan filter pencarian Anda.
                </p>
            </div>
        </div>
        @endif
    </div>

    <div class="flex gap-2 mt-4">
        <x-filament::button wire:click="exportToExcel" color="success" icon="heroicon-o-table-cells">
            Export Excel
        </x-filament::button>

        <x-filament::button wire:click="exportToPdf" color="danger" icon="heroicon-o-document-text">
            Export PDF
        </x-filament::button>
    </div>

</x-filament::page>
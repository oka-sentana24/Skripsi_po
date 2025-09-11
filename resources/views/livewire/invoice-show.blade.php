<div id="printable-area" class="max-w-2xl mx-auto p-6 font-sans bg-gray-50">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center border-b border-gray-200 px-6 py-4 gap-6">
            <img src="/Images/logo.png" alt="Logo Klinik" 
                class="w-16 h-16 object-contain rounded-lg flex-shrink-0">

            <div>
                <h1 class="text-xl font-bold text-green-800 leading-tight">
                    Aire Aesthetic Bali
                </h1>
                <p class="text-sm text-green-700 max-w-md leading-snug">
                    Jl. Tanah Putih No.32, Darmasaba, Kec. Abiansemal, Kabupaten Badung, Bali 80352
                </p>
            </div>
        </div>

        {{-- Info Pembayaran --}}
        <div class="px-6 py-4 text-sm text-gray-700">
            <p><span class="font-semibold">ID Pembayaran:</span> {{ $pembayaran->id }}</p>
            <p><span class="font-semibold">Tanggal:</span> {{ $pembayaran->created_at->format('d/m/Y') }}</p>
            <p><span class="font-semibold">Status Pembayaran:</span> {{ $pembayaran->status }}</p>
            <p><span class="font-semibold">Pasien:</span> {{ $pembayaran->pendaftaran->pasien->nama ?? '-' }}</p>
        </div>

        {{-- Detail Layanan --}}
        <div class="px-6 py-4">
            <h3 class="text-base font-semibold text-gray-800 mb-2">Detail Layanan</h3>
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border px-2 py-1 text-left">Layanan</th>
                        <th class="border px-2 py-1 text-center">Terapis</th>
                        <th class="border px-2 py-1 text-right">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $layanans = $pembayaran->pendaftaran->tindakan
                            ->flatMap(function($t) {
                                return $t->layanans->map(function($l) use ($t) {
                                    return (object) [
                                        'nama' => $l->nama,
                                        'harga' => $l->harga,
                                        'terapis' => $t->terapis ?? null,
                                    ];
                                });
                            });
                    @endphp

                    @forelse($layanans as $layanan)
                        <tr>
                            <td class="border px-2 py-1">{{ $layanan->nama }}</td>
                            <td class="border px-2 py-1 text-center">{{ $layanan->terapis->nama ?? '-' }}</td>
                            <td class="border px-2 py-1 text-right">{{ number_format($layanan->harga ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-2 text-gray-500">Tidak ada layanan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Detail Produk --}}
        <div class="px-6 py-4">
            <h3 class="text-base font-semibold text-gray-800 mb-2">Detail Produk</h3>
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border px-2 py-1 text-left">Produk</th>
                        <th class="border px-2 py-1 text-center">Jumlah</th>
                        <th class="border px-2 py-1 text-right">Harga</th>
                        <th class="border px-2 py-1 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran->produk ?? collect() as $produk)
                        <tr>
                            <td class="border px-2 py-1">{{ $produk->nama }}</td>
                            <td class="border px-2 py-1 text-center">{{ $produk->pivot->jumlah ?? 0 }}</td>
                            <td class="border px-2 py-1 text-right">{{ number_format($produk->pivot->harga ?? 0, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1 text-right">
                                {{ number_format(($produk->pivot->jumlah ?? 0) * ($produk->pivot->harga ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-2 text-gray-500">Tidak ada produk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Ringkasan --}}
        <div class="px-6 py-4 bg-gray-50">
            <h3 class="text-base font-semibold text-gray-800 mb-2">Ringkasan</h3>
            @php
                $totalLayanan = $layanans->sum(fn($l) => $l->harga ?? 0);
                $totalProduk = $pembayaran->produk->sum(fn($p) => ($p->pivot->jumlah ?? 0) * ($p->pivot->harga ?? 0));
                $diskon = $pembayaran->diskon ?? 0;
                $totalBayar = $totalLayanan + $totalProduk - $diskon;
            @endphp
            <table class="w-full text-sm border-collapse">
                <tr>
                    <td class="py-1 text-gray-600">Total Layanan</td>
                    <td class="py-1 text-right font-medium">{{ number_format($totalLayanan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Total Produk</td>
                    <td class="py-1 text-right font-medium">{{ number_format($totalProduk, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Diskon</td>
                    <td class="py-1 text-right font-medium text-red-600">-{{ number_format($diskon, 0, ',', '.') }}</td>
                </tr>
                <tr class="border-t border-gray-300 font-bold text-lg text-gray-800">
                    <td class="py-2">Total Bayar</td>
                    <td class="py-2 text-right">{{ number_format($totalBayar, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        {{-- Footer --}}
        <div class="text-center text-xs text-gray-500 px-6 py-3 border-t border-gray-200">
            Terima kasih telah melakukan pembayaran di 
            <span class="font-semibold text-green-700">Aire Aesthetic Bali</span>
        </div>
    </div>
</div>

{{-- Print Style --}}
<style>
@media print {
    body * { visibility: hidden; }
    #printable-area, #printable-area * { visibility: visible; }
    #printable-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: none !important;
    }
    button, .no-print { display: none !important; }
}
</style>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $reportTitle ?? 'Laporan Klinik' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 25px;
            color: #222;
        }

        /* ====== KOP SURAT ====== */
        .kop-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 25px;
            padding-bottom: 10px;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo {
            text-align: center;
            width: 120px;
        }

        .kop-logo img {
            height: 75px;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 20px;
        }

        .kop-text p {
            margin: 2px 0;
            font-size: 12px;
        }

        /* ====== JUDUL ====== */
        h3 {
            text-align: center;
            margin-bottom: 12px;
            text-transform: uppercase;
            font-size: 16px;
            border-bottom: 1px solid #aaa;
            padding-bottom: 5px;
        }

        /* ====== TABEL ====== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* ====== TOTAL ====== */
        tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
            border-top: 2px solid #555;
        }

        /* ====== RINGKASAN ====== */
        .summary {
            margin-top: 25px;
            text-align: center;
        }

        .summary table {
            border: none;
            margin: 0 auto;
        }

        .summary td {
            padding: 4px 10px;
        }

        .summary td:first-child {
            font-weight: bold;
            text-align: right;
        }

        /* ====== FOOTER ====== */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 10px;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>

    {{-- ==== KOP SURAT ==== --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @php
                    $logoPath = public_path('images/logo.png');
                @endphp
                @if (file_exists($logoPath))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}" alt="Logo Klinik">
                @endif
            </td>
            <td class="kop-text">
                <h2>Aire Aesthetic Bali</h2>
                <p>Jl. Tanah Putih No.32, Darmasaba, Kec. Abiansemal, Kabupaten Badung, Bali 80352</p>
                <p>Telp: 0812-3967-6446</p>
            </td>
        </tr>
    </table>

    {{-- ==== JUDUL LAPORAN ==== --}}
    <h3>{{ $reportTitle ?? 'Laporan' }}</h3>

    {{-- ==== TABEL DATA ==== --}}
    <table>
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData as $row)
                <tr>
                    @foreach ($row as $value)
                        <td>
                            {{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>

        {{-- ==== TOTAL OTOMATIS ==== --}}
        @php
            $numericTotals = [];
            foreach ($reportData as $row) {
                foreach ($row as $key => $value) {
                    if (is_numeric($value)) {
                        $numericTotals[$key] = ($numericTotals[$key] ?? 0) + $value;
                    }
                }
            }
        @endphp

        @if (!empty($numericTotals))
            <tfoot>
                <tr>
                    @foreach ($headers as $index => $header)
                        @php
                            $key = array_keys($reportData->first() ?? [])[$index] ?? null;
                        @endphp
                        @if ($loop->first)
                            <td>Total</td>
                        @elseif (isset($numericTotals[$key]))
                            <td>{{ number_format($numericTotals[$key], 0, ',', '.') }}</td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- ==== RINGKASAN LAPORAN TANPA RATA-RATA ==== --}}
    @if (!empty($summary))
        <div class="summary">
            <h4>Ringkasan</h4>
            <table>
                @foreach ($summary as $label => $value)
                    @continue(str_contains(strtolower($label), 'rata')) {{-- abaikan jika label mengandung "rata" --}}
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif

    <footer>
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
    </footer>

</body>
</html>

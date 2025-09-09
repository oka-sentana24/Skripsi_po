<div id="printable-area">
    <div style="
        max-width: 460px;
        margin: 0 auto;
        padding: 1rem;
        font-family: 'Segoe UI', sans-serif;
        background: #fef3f3;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    ">

        <div style="
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
        ">

            {{-- Header Klinik --}}
            <div style="
                display: flex;
                align-items: center;
                margin-bottom: 1.5rem;
                border-bottom: 1px solid #d1d5db;
                padding-bottom: 1rem;
            ">
                <img src="/Images/logo.png" alt="Logo Klinik" style="
                    width: 64px;
                    height: 64px;
                    object-fit: contain;
                    border-radius: 8px;
                    margin-right: 1rem;
                ">
                
                <div>
                    <h1 style="
                        margin: 0;
                        font-size: 1.2rem;
                        font-weight: bold;
                        color: #7f1d1d;
                    ">Aire Aesthetic Bali</h1>
                    <p style="
                        margin: 0;
                        font-size: 0.85rem;
                        color: #7f1d1d;
                    ">
                        Jl. Tanah Putih No.32, <br/>Darmasaba, Kec. Abiansemal, Kabupaten Badung, Bali 80352
                    </p>
                </div>
            </div>

            {{-- Judul --}}
            <div style="text-align: center; margin-bottom: 1rem;">
                <h2 style="
                    margin: 0;
                    font-size: 1.5rem;
                    font-weight: bold;
                    color: #b91c1c;
                ">Kartu Pasien</h2>
            </div>

            {{-- Konten Pasien --}}
            <table style="width: 100%; font-size: 0.95rem; color: #374151;">
                <tr>
                    <td style="font-weight: 600; padding: 6px 0;">No. RM</td>
                    <td style="text-align: right;">{{ $pasien->no_rm }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 600; padding: 6px 0;">Nama</td>
                    <td style="text-align: right;">{{ $pasien->nama }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 600; padding: 6px 0;">Alamat</td>
                    <td style="text-align: right;">{{ $pasien->alamat }}</td>
                </tr>
                <tr>
                    <td style="font-weight: 600; padding: 6px 0;">Tanggal Lahir</td>
                    <td style="text-align: right;">
                        {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d/m/Y') }}
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: 600; padding: 6px 0;">No. HP</td>
                    <td style="text-align: right;">{{ $pasien->no_hp }}</td>
                </tr>
            </table>

        </div>
    </div>
</div>

{{-- Gaya saat print --}}
<style>
@media print {
    body * {
        visibility: hidden;
    }

    #printable-area, #printable-area * {
        visibility: visible;
    }

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

    button, .no-print {
        display: none !important;
    }
}
</style>

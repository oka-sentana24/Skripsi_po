<div class="modal-body p-8 space-y-6 border shadow-lg">
    <div class="py-2">
        <!-- Logo Klinik -->
        <!-- <img src="{{ asset('images/logo-clinic.png') }}" alt="Logo Klinik" class="max-h-24 mb-6 mx-auto"> -->

        <!-- Nama dan Alamat Klinik -->
        <div class="text-center border-b">
            <h4 class="font-semibold text-blue-600">AIRE CLINIK</h4>
            <p class="text-gray-600 mt-2 pb-3">Jl. Tanah Putih No.32, Darmasaba, Kec. Abiansemal, Kabupaten Badung, Bali 80352</p>
        </div>
    </div>

    <!-- Detail Antrean -->
    <div class="border-t-2 border-gray-300 text-center">
        <h5 class="text-xl font-semibold text-gray-800">UMUM</h5>
        <!-- Emphasize the queue number -->
        <p style="font-size:100px;" class="font-semibold">
            {{ $queue->queue_number }}
        </p>

        <div class="mt-4 space-y-2 text-center">
        <p class="text-xl font-semibold uppercase">{{ strtoupper($patient->nama_lengkap) }}</p>

            <div class="py-3">
                <label for="">NRM:</label>
                <p>{{ $patient->nomor_rekam_medik }}</p>
                <label for="">Tgl.Lahir:</label>
                <p>{{ $patient->tanggal_lahir }}</p>
                <label for="">Jenis Kelamin:</label>
                <p>{{ $patient->jenis_kelamin }}</p>
            </div>
            <div class="py-3">
                <label for="">Tanggal Registrasi:</label>
                <p>{{ \Carbon\Carbon::parse($patient->registration_dater)->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Disclaimer Antrean -->
    <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-gray-800 rounded-lg shadow-md border-t text-center text-sm">
        <strong class="font-medium">Perhatian:</strong>
        <p class="mb-0">Nomor antrean ini hanya berlaku pada tanggal yang tercetak. Harap datang tepat waktu.</p>
    </div>
</div>
<div>
    <x-filament::button onClick="window.print();" class="w-full">
    Print
    </x-filament::button>
</div>
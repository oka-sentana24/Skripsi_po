<x-filament-panels::page>
    <div class="flex flex-col items-center space-y-4">
        {{-- Logo --}}
        <img src="{{ asset('images/logo.png') }}" alt="Logo Klinik" class="h-20">

        {{-- Judul --}}
        <h2 class="text-xl font-bold">Selamat Datang di Sistem Klinik</h2>

        {{-- Form Login --}}
        <x-filament-panels::auth.login-form />
    </div>

</x-filament-panels::page>

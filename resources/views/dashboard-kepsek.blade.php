<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Kepala Sekolah</h2>
    </x-slot>

    <div class="bg-white rounded-lg shadow p-6 text-gray-500">
        Selamat datang, <strong>{{ Auth::user()->nama }}</strong> (Kepala Sekolah). Halaman ini akan dikembangkan sesuai Bab 5.2 & 7 PRD (Dashboard, Rekap Laporan read-only).
    </div>
</x-app-layout>
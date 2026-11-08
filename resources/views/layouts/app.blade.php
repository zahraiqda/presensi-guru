<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-krem">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Presensi Guru') }} - SMK Islam Al-Amanah Salem</title>

    <style>[x-cloak]{display:none !important;}</style>

    <!-- Google Fonts: Plus Jakarta Sans & Amiri -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-krem text-teks flex flex-col min-h-screen selection:bg-biru selection:text-emas-terang" x-data="{ mobileMenuOpen: false }">

    @php
        $role = Auth::user()->role;

        $iconDashboard = 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6';
        $iconGuru = 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z';
        $iconLaporan = 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
        $iconArsip = 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4';
        $iconLog = 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z';
        $iconKepsek = 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z';

        $menu = match ($role) {
            'admin' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => $iconDashboard],
                ['label' => 'Data Guru', 'route' => 'admin.guru.index', 'active' => 'admin.guru.*', 'icon' => $iconGuru],
                ['label' => 'Rekap Laporan', 'route' => 'admin.laporan.index', 'active' => 'admin.laporan.*', 'icon' => $iconLaporan],
                ['label' => 'Arsip Presensi', 'route' => 'admin.arsip.index', 'active' => 'admin.arsip.*', 'icon' => $iconArsip],
                ['label' => 'Kepala Sekolah', 'route' => 'admin.kepsek.index', 'active' => 'admin.kepsek.*', 'icon' => $iconKepsek],
            ],
            'kepala_sekolah' => [
                ['label' => 'Dashboard', 'route' => 'kepsek.dashboard', 'active' => 'kepsek.dashboard', 'icon' => $iconDashboard],
                ['label' => 'Rekap Laporan', 'route' => 'kepsek.laporan.index', 'active' => 'kepsek.laporan.*', 'icon' => $iconLaporan],
                ['label' => 'Arsip Presensi', 'route' => 'kepsek.arsip.index', 'active' => 'kepsek.arsip.*', 'icon' => $iconArsip],
                ['label' => 'Log Riwayat Presensi', 'route' => 'kepsek.log.index', 'active' => 'kepsek.log.*', 'icon' => $iconLog],
            ],
            default => [
                ['label' => 'Dashboard Presensi', 'route' => 'guru.dashboard', 'active' => 'guru.dashboard', 'icon' => $iconDashboard],
            ],
        };
    @endphp

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 bg-biru text-white border-b-4 border-emas shadow-md transition-all">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 gap-3">

                <!-- Logo & Brand -->
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white border-2 border-emas p-0.5 flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Islam Al-Amanah" class="w-full h-full rounded-full object-cover">
                        </div>
                        <div class="hidden sm:block">
                            <span class="block font-bold text-white text-sm sm:text-base tracking-tight leading-tight whitespace-nowrap">
                                Presensi Guru
                            </span>
                            <span class="block text-[11px] font-medium text-[#cdd5ea] leading-tight whitespace-nowrap">
                                SMK Islam Al-Amanah Salem
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-1.5">
                    @foreach ($menu as $item)
                        <a href="{{ route($item['route']) }}"
                           class="px-3 py-2 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-150 inline-flex items-center gap-1.5 whitespace-nowrap shrink-0 {{ request()->routeIs($item['active']) ? 'bg-white/15 text-emas-terang border border-emas/40 shadow-xs backdrop-blur-xs font-bold' : 'text-[#cdd5ea] hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 opacity-90 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <!-- User Profile & Action Pill -->
                <div class="hidden lg:flex items-center gap-2.5 shrink-0">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl border border-emas/30 bg-biru-tua/80 hover:bg-biru-tua transition-colors"
                       title="Edit Profil Akun">
                        <div class="w-7 h-7 rounded-lg bg-emas text-biru-tua font-bold text-xs flex items-center justify-center shadow-xs shrink-0">
                            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                        </div>
                        <div class="text-left max-w-[120px] xl:max-w-[160px]">
                            <span class="block text-xs font-semibold text-white leading-tight truncate">
                                {{ Auth::user()->nama }}
                            </span>
                            <span class="inline-block text-[10px] font-bold text-emas-terang leading-tight">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                            </span>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-rose-200 hover:text-white hover:bg-rose-900/50 rounded-xl transition-colors border border-rose-500/20"
                                title="Keluar dari akun">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>

                <!-- Mobile / Tablet Menu Button -->
                <div class="flex items-center lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="p-2 rounded-lg text-white hover:bg-white/10 focus:outline-none transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                x-show="!mobileMenuOpen" d="M4 6h16M4 12h16M4 18h16" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                x-show="mobileMenuOpen" x-cloak d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="lg:hidden border-t border-emas/30 bg-biru shadow-xl">
            <div class="px-4 py-3 space-y-1">
                @foreach ($menu as $item)
                    <a href="{{ route($item['route']) }}"
                       class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs($item['active']) ? 'bg-biru-tua text-emas-terang border border-emas/40' : 'text-[#cdd5ea] hover:bg-white/10 hover:text-white' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach

                <div class="border-t border-white/15 my-2 pt-2">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-white hover:bg-white/10">
                        <span class="w-6 h-6 rounded-full bg-emas text-biru-tua text-xs font-bold flex items-center justify-center">
                            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                        </span>
                        <span>Profil: <strong>{{ Auth::user()->nama }}</strong></span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-rose-300 hover:bg-rose-900/30 font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    @isset($header)
        <div class="bg-white border-b border-garis">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                {{ $header }}
            </div>
        </div>
    @endisset

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 flex-1 w-full">
        {{ $slot }}
    </main>

    <!-- Footer (Matching Login Design) -->
    <footer class="mt-auto border-t border-garis bg-krem py-4 text-center text-xs text-redup">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                &copy; {{ date('Y') }} <strong class="text-teks">SMK Islam Al-Amanah Salem</strong> &middot; Sistem Presensi Terintegrasi
            </div>
            <div class="flex items-center gap-4 text-[11px] text-redup">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-hijau"></span>
                    Geofencing Aktif
                </span>
                <span>&bull;</span>
                <span>Salem, Brebes</span>
            </div>
        </div>
    </footer>

</body>
</html>
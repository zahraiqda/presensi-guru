<x-app-layout>
    @php
        $belumPresensiCount = max(0, $totalGuru - ($hadirHariIni + $sakitHariIni + $izinHariIni));
        $persenHadir = $totalGuru > 0 ? round(($hadirHariIni / $totalGuru) * 100) : 0;
        $persenSakit = $totalGuru > 0 ? round(($sakitHariIni / $totalGuru) * 100) : 0;
        $persenIzin = $totalGuru > 0 ? round(($izinHariIni / $totalGuru) * 100) : 0;
        $persenBelum = $totalGuru > 0 ? round(($belumPresensiCount / $totalGuru) * 100) : 0;
    @endphp

    <div class="space-y-6"
         x-data="{
             time: '',
             date: '',
             search: '',
             filterStatus: 'all',
             teachers: {{ \Illuminate\Support\Js::from($ringkasanGuru) }},
             initDashboard() {
                 const tick = () => {
                     const now = new Date();
                     this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
                     this.date = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                 };
                 tick();
                 setInterval(tick, 1000);
             },
             get filteredTeachers() {
                 return this.teachers.filter(t => {
                     const query = this.search.trim().toLowerCase();
                     const matchesSearch = !query ||
                         (t.nama && t.nama.toLowerCase().includes(query)) ||
                         (t.nik && t.nik.toLowerCase().includes(query)) ||
                         (t.mata_pelajaran && t.mata_pelajaran.toLowerCase().includes(query)) ||
                         (t.label_mapel && t.label_mapel.toLowerCase().includes(query));
                     const matchesStatus = this.filterStatus === 'all' ||
                         t.status.toLowerCase() === this.filterStatus.toLowerCase();
                     return matchesSearch && matchesStatus;
                 });
             }
         }"
         x-init="initDashboard()">

        <!-- Hero Welcome Banner (Matching Login Header Tone) -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-biru-tua via-biru to-biru-tua text-white p-6 sm:p-8 shadow-md border-b-4 border-emas">
            <!-- Subtle Watermark Logo -->
            <div class="absolute -right-8 -bottom-10 w-64 h-64 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo.png') }}" alt="Watermark Logo" class="w-full h-full object-contain">
            </div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Left Greeting -->
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-emas/40 text-emas-terang text-xs font-semibold tracking-wide">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-3.5 h-3.5 object-contain rounded-full">
                        <span>SMK ISLAM AL AMANAH SALEM &middot; GEOFENCING AKTIF</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                        Selamat Datang, {{ Auth::user()->nama }} 👋
                    </h1>
                    <p class="text-sm sm:text-base text-[#cdd5ea] max-w-2xl leading-relaxed">
                        Pantau kedisiplinan dan rekap kehadiran dewan guru SMK Islam Al-Amanah Salem secara langsung hari ini.
                    </p>
                </div>

                <!-- Right Live Clock (Matching Login Clock Style) -->
                <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-biru-tua/80 border border-emas/30 shadow-inner self-start lg:self-auto">
                    <div class="w-10 h-10 rounded-lg bg-emas/20 border border-emas/40 text-emas-terang flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <span class="block text-[11px] font-medium text-[#cdd5ea]" x-text="date || '{{ now()->translatedFormat('l, d F Y') }}'"></span>
                        <span class="block text-base sm:text-lg font-bold font-mono text-emas-terang tracking-wider" x-text="time || '{{ now()->format('H:i:s') }} WIB'"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5 Metric KPI Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            <!-- Card 1: Total Guru (Biru) -->
            <div class="bg-white rounded-xl p-5 border border-garis shadow-xs hover:shadow-md transition-all duration-200 border-t-4 border-t-biru flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-redup uppercase tracking-wider">Total Dewan Guru</span>
                        <div class="w-10 h-10 rounded-lg bg-biru-muda text-biru flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold text-biru-tua">{{ $totalGuru }}</span>
                        <span class="text-xs font-medium text-redup">Guru / Staf</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#efe8d8] flex items-center justify-between text-xs text-biru font-bold">
                    <a href="{{ route('admin.guru.index') }}" class="hover:underline flex items-center gap-1">
                        <span>Kelola Master Guru</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Hadir Hari Ini (Hijau) -->
            <div class="bg-white rounded-xl p-5 border border-garis shadow-xs hover:shadow-md transition-all duration-200 border-t-4 border-t-hijau flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-redup uppercase tracking-wider">Hadir Hari Ini</span>
                        <div class="w-10 h-10 rounded-lg bg-hijau-muda text-hijau flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold text-hijau">{{ $hadirHariIni }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-hijau-muda text-hijau">
                            {{ $persenHadir }}%
                        </span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#efe8d8] text-xs text-redup">
                    Tercatat presensi masuk
                </div>
            </div>

            <!-- Card 3: Izin Hari Ini (Biru Muda) -->
            <div class="bg-white rounded-xl p-5 border border-garis shadow-xs hover:shadow-md transition-all duration-200 border-t-4 border-t-biru flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-redup uppercase tracking-wider">Izin</span>
                        <div class="w-10 h-10 rounded-lg bg-biru-muda text-biru flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold text-biru">{{ $izinHariIni }}</span>
                        <span class="text-xs font-medium text-redup">Guru</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#efe8d8] text-xs text-redup">
                    Konfirmasi izin dinas/pribadi
                </div>
            </div>

            <!-- Card 4: Sakit Hari Ini (Emas) -->
            <div class="bg-white rounded-xl p-5 border border-garis shadow-xs hover:shadow-md transition-all duration-200 border-t-4 border-t-emas flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-redup uppercase tracking-wider">Sakit</span>
                        <div class="w-10 h-10 rounded-lg bg-emas-muda text-emas-tua flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold text-emas-tua">{{ $sakitHariIni }}</span>
                        <span class="text-xs font-medium text-redup">Guru</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#efe8d8] text-xs text-redup">
                    Keterangan sakit
                </div>
            </div>

            <!-- Card 5: Belum Presensi (Merah) -->
            <div class="bg-white rounded-xl p-5 border border-garis shadow-xs hover:shadow-md transition-all duration-200 border-t-4 border-t-merah flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-redup uppercase tracking-wider">Belum Presensi</span>
                        <div class="w-10 h-10 rounded-lg bg-merah-muda text-merah flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold text-merah">{{ $belumPresensiCount }}</span>
                        <span class="text-xs font-medium text-redup">Guru</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#efe8d8] text-xs text-merah font-semibold">
                    Belum mencatat kehadiran
                </div>
            </div>

        </div>

        <!-- Tingkat Kehadiran Harian & School Config Strip -->
        <div class="bg-white rounded-xl p-5 sm:p-6 border border-garis shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-biru text-white flex items-center justify-center text-xs font-bold">
                        📊
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-biru-tua">Tingkat Kehadiran Harian</h3>
                        <p class="text-xs text-redup">Proporsi kehadiran dewan guru berdasarkan status hari ini</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="px-2.5 py-1 rounded-full bg-hijau-muda text-hijau font-semibold">Hadir: <strong>{{ $hadirHariIni }}</strong> ({{ $persenHadir }}%)</span>
                    <span class="px-2.5 py-1 rounded-full bg-biru-muda text-biru font-semibold">Izin: <strong>{{ $izinHariIni }}</strong></span>
                    <span class="px-2.5 py-1 rounded-full bg-emas-muda text-emas-tua font-semibold">Sakit: <strong>{{ $sakitHariIni }}</strong></span>
                    <span class="px-2.5 py-1 rounded-full bg-merah-muda text-merah font-semibold">Belum: <strong>{{ $belumPresensiCount }}</strong></span>
                </div>
            </div>

            <!-- Multi-segment Progress Bar -->
            <div class="w-full h-3 rounded-full bg-[#efe8d8] overflow-hidden flex shadow-inner">
                @if ($totalGuru > 0)
                    <div style="width: {{ $persenHadir }}%" class="bg-hijau h-full transition-all duration-500" title="Hadir: {{ $hadirHariIni }}"></div>
                    <div style="width: {{ $persenIzin }}%" class="bg-biru h-full transition-all duration-500" title="Izin: {{ $izinHariIni }}"></div>
                    <div style="width: {{ $persenSakit }}%" class="bg-emas h-full transition-all duration-500" title="Sakit: {{ $sakitHariIni }}"></div>
                    <div style="width: {{ $persenBelum }}%" class="bg-garis h-full transition-all duration-500" title="Belum: {{ $belumPresensiCount }}"></div>
                @else
                    <div class="w-full bg-garis h-full"></div>
                @endif
            </div>

            <!-- School Rule Quick Badges (Matching Login Info) -->
            <div class="pt-3 border-t border-garis flex flex-wrap items-center gap-2 sm:gap-3 text-xs">
                <span class="font-bold text-teks flex items-center gap-1.5 mr-1">
                    <span class="w-2 h-2 rounded-full bg-biru"></span>
                    Standar Presensi:
                </span>
                <span class="bg-biru-muda border border-[#cbd7ee] text-biru-tua font-semibold px-3 py-1 rounded-md flex items-center gap-1">
                    <span>📍</span> Geofence Radius {{ $pengaturan->radius ?? 60 }}m
                </span>
                <span class="bg-emas-muda border border-[#ecd9a0] text-[#7a560a] font-semibold px-3 py-1 rounded-md flex items-center gap-1">
                    <span>⏰</span> Toleransi Masuk s/d {{ \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_toleransi)->format('H:i') }} WIB
                </span>
                <span class="bg-[#fdfcf9] border border-garis text-teks font-semibold px-3 py-1 rounded-md flex items-center gap-1">
                    <span>🚪</span> Jam Pulang Minimal {{ \Illuminate\Support\Carbon::parse($pengaturan->jam_pulang_minimal)->format('H:i') }} WIB
                </span>
            </div>
        </div>

        <!-- 2-Column Responsive Dashboard Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Primary Column (8 Cols): Ringkasan Kehadiran Guru Hari Ini -->
            <div class="lg:col-span-8 bg-white rounded-xl border border-garis shadow-xs overflow-hidden">

                <!-- Table Header with Search & Status Filter -->
                <div class="p-5 border-b border-garis space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-biru-muda text-biru flex items-center justify-center text-xs font-bold">
                                📋
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-biru-tua">Ringkasan Kehadiran Guru Hari Ini</h3>
                                <p class="text-xs text-redup">Daftar presensi seluruh dewan guru secara real-time</p>
                            </div>
                        </div>
                        <div class="text-xs text-redup font-medium bg-krem border border-garis px-3 py-1 rounded-md">
                            Menampilkan <span class="font-bold text-biru" x-text="filteredTeachers.length"></span> dari <span class="font-bold text-teks">{{ count($ringkasanGuru) }}</span> guru
                        </div>
                    </div>

                    <!-- Filter Controls (Search + Status Tabs) -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Instant Search Input -->
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-redup">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   x-model="search"
                                   placeholder="Cari berdasarkan nama, NIK, atau guru mapel..."
                                   class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-garis text-xs sm:text-sm focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all placeholder:text-redup bg-[#fdfcf9]">
                        </div>

                        <!-- Status Filter Buttons (Matching Login Scheme) -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                            <button type="button"
                                    @click="filterStatus = 'all'"
                                    :class="filterStatus === 'all'
                                        ? 'bg-biru text-white font-bold shadow-xs'
                                        : 'bg-[#fdfcf9] text-redup border border-garis hover:bg-krem'"
                                    class="px-3.5 py-2 rounded-lg text-xs transition-colors shrink-0">
                                Semua
                            </button>
                            <button type="button"
                                    @click="filterStatus = 'hadir'"
                                    :class="filterStatus === 'hadir'
                                        ? 'bg-hijau text-white font-bold shadow-xs'
                                        : 'bg-hijau-muda text-hijau hover:opacity-90'"
                                    class="px-3.5 py-2 rounded-lg text-xs transition-colors shrink-0">
                                Hadir
                            </button>
                            <button type="button"
                                    @click="filterStatus = 'izin'"
                                    :class="filterStatus === 'izin'
                                        ? 'bg-biru text-white font-bold shadow-xs'
                                        : 'bg-biru-muda text-biru hover:opacity-90'"
                                    class="px-3.5 py-2 rounded-lg text-xs transition-colors shrink-0">
                                Izin
                            </button>
                            <button type="button"
                                    @click="filterStatus = 'sakit'"
                                    :class="filterStatus === 'sakit'
                                        ? 'bg-emas text-white font-bold shadow-xs'
                                        : 'bg-emas-muda text-emas-tua hover:opacity-90'"
                                    class="px-3.5 py-2 rounded-lg text-xs transition-colors shrink-0">
                                Sakit
                            </button>
                            <button type="button"
                                    @click="filterStatus = 'belum presensi'"
                                    :class="filterStatus === 'belum presensi'
                                        ? 'bg-merah text-white font-bold shadow-xs'
                                        : 'bg-merah-muda text-merah hover:opacity-90'"
                                    class="px-3.5 py-2 rounded-lg text-xs transition-colors shrink-0">
                                Belum
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modern Table Container -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm divide-y divide-garis">
                        <thead class="bg-biru-muda text-biru-tua font-bold text-xs tracking-wider uppercase">
                            <tr>
                                <th class="px-5 py-3.5">Nama Guru</th>
                                <th class="px-5 py-3.5">NIK</th>
                                <th class="px-5 py-3.5">Jam Masuk</th>
                                <th class="px-5 py-3.5">Jam Pulang</th>
                                <th class="px-5 py-3.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#efe8d8] bg-white">
                            <template x-for="(g, index) in filteredTeachers" :key="g.nik + index">
                                <tr class="hover:bg-[#faf7f0] transition-colors">
                                    <!-- Nama Guru with Avatar -->
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-biru text-emas-terang text-xs font-bold flex items-center justify-center shrink-0 border border-emas/30 shadow-xs">
                                                <span x-text="g.nama ? g.nama.charAt(0).toUpperCase() : 'G'"></span>
                                            </div>
                                            <div>
                                                <span class="block font-semibold text-teks text-sm leading-tight" x-text="g.nama"></span>
                                                <span class="block text-[11px] text-redup mt-0.5" x-text="g.label_mapel || (g.mata_pelajaran ? 'Guru ' + g.mata_pelajaran : 'Tenaga Pengajar')"></span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- NIK -->
                                    <td class="px-5 py-3.5 whitespace-nowrap text-xs text-redup font-mono">
                                        <span class="bg-krem px-2 py-0.5 rounded border border-garis" x-text="g.nik"></span>
                                    </td>

                                    <!-- Jam Masuk -->
                                    <td class="px-5 py-3.5 whitespace-nowrap text-xs">
                                        <template x-if="g.jam_masuk">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-hijau-muda text-hijau font-bold font-mono">
                                                <svg class="w-3.5 h-3.5 text-hijau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                    <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                                                </svg>
                                                <span x-text="g.jam_masuk.substring(11, 16) || g.jam_masuk"></span>
                                            </span>
                                        </template>
                                        <template x-if="!g.jam_masuk">
                                            <span class="text-redup font-mono">-</span>
                                        </template>
                                    </td>

                                    <!-- Jam Pulang -->
                                    <td class="px-5 py-3.5 whitespace-nowrap text-xs">
                                        <template x-if="g.jam_pulang">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-biru-muda text-biru font-bold font-mono">
                                                <svg class="w-3.5 h-3.5 text-biru" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                    <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                                                </svg>
                                                <span x-text="g.jam_pulang.substring(11, 16) || g.jam_pulang"></span>
                                            </span>
                                        </template>
                                        <template x-if="!g.jam_pulang">
                                            <span class="text-redup font-mono">-</span>
                                        </template>
                                    </td>

                                    <!-- Status Pill -->
                                    <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                        <template x-if="g.status === 'Izin'">
                                            <div class="inline-flex flex-col items-center gap-1">
                                                <template x-if="g.approval_status === 'disetujui'">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border bg-hijau-muda text-hijau border-hijau/30">
                                                        ✓ Izin (Disetujui Kepala Sekolah)
                                                    </span>
                                                </template>
                                                <template x-if="g.approval_status === 'ditolak'">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border bg-merah-muda text-merah border-merah/30">
                                                        ✕ Izin (Ditolak Kepala Sekolah)
                                                    </span>
                                                </template>
                                                <template x-if="!g.approval_status || g.approval_status === 'menunggu'">
                                                    <div class="inline-flex flex-col items-center gap-0.5">
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border bg-emas-muda text-emas-tua border-emas/30">
                                                            ⏳ Izin (Menunggu Persetujuan)
                                                        </span>
                                                        <template x-if="g.token_izin">
                                                            <a :href="'/izin/persetujuan/' + g.token_izin" target="_blank"
                                                               class="text-[10px] text-biru hover:underline font-semibold">
                                                                Tinjau / Setujui →
                                                            </a>
                                                        </template>
                                                    </div>
                                                </template>
                                                <template x-if="g.keterangan">
                                                    <p class="text-[10px] text-redup mt-0.5 italic" x-text="'(' + g.keterangan + ')'"></p>
                                                </template>
                                                <template x-if="g.bukti_izin">
                                                    <div class="mt-1">
                                                        <a :href="'/storage/' + g.bukti_izin" target="_blank"
                                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-biru-muda text-biru border border-biru/20 hover:bg-biru/15 font-semibold text-[10px] transition-colors">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            <span>Bukti Izin</span>
                                                        </a>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        <template x-if="g.status !== 'Izin'">
                                            <div>
                                                <span :class="{
                                                    'bg-hijau-muda text-hijau': g.status === 'Hadir',
                                                    'bg-emas-muda text-emas-tua': g.status === 'Sakit',
                                                    'bg-merah-muda text-merah': g.status === 'Belum Presensi'
                                                }" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold">
                                                    <span :class="{
                                                        'bg-hijau': g.status === 'Hadir',
                                                        'bg-emas': g.status === 'Sakit',
                                                        'bg-merah': g.status === 'Belum Presensi'
                                                    }" class="w-1.5 h-1.5 rounded-full"></span>
                                                    <span x-text="g.status"></span>
                                                </span>
                                                <template x-if="g.keterangan && g.status === 'Sakit'">
                                                    <p class="text-[10px] text-redup mt-0.5 italic" x-text="'(' + g.keterangan + ')'"></p>
                                                </template>
                                                <template x-if="g.bukti_sakit">
                                                    <div class="mt-1">
                                                        <a :href="'/storage/' + g.bukti_sakit" target="_blank"
                                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-merah-muda text-merah border border-merah/20 hover:bg-merah/15 font-semibold text-[10px] transition-colors">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            <span>Bukti Foto</span>
                                                        </a>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </td>
                                </tr>
                            </template>

                            <!-- Empty Search Result Template -->
                            <tr x-show="filteredTeachers.length === 0" x-cloak>
                                <td colspan="5" class="px-6 py-12 text-center text-redup">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div class="w-12 h-12 rounded-full bg-krem text-redup flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-teks">Tidak ada data guru yang cocok</p>
                                        <p class="text-xs text-redup">Coba kata kunci lain atau ubah filter status.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div class="p-4 border-t border-garis bg-krem flex flex-col sm:flex-row items-center justify-between text-xs text-redup gap-2">
                    <span>Diperbarui otomatis saat ada transaksi presensi masuk atau pulang.</span>
                    <a href="{{ route('admin.laporan.index') }}" class="font-bold text-biru hover:text-biru-tua flex items-center gap-1">
                        <span>Lihat Rekap Selengkapnya</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

            </div>

            <!-- Secondary Column (4 Cols): Aktivitas Presensi Terkini -->
            <div class="lg:col-span-4 bg-white rounded-xl border border-garis shadow-xs overflow-hidden">
                <div class="p-5 border-b border-garis flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-biru-tua">Aktivitas Terkini</h3>
                        <p class="text-xs text-redup">10 catatan presensi terbaru hari ini</p>
                    </div>
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-hijau opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-hijau"></span>
                    </span>
                </div>

                <div class="p-5">
                    @forelse ($presensiHariIni as $index => $p)
                        <div class="relative pl-6 pb-6 last:pb-2 group">
                            <!-- Connecting vertical line -->
                            @if (!$loop->last)
                                <div class="absolute left-2 top-3 bottom-0 w-0.5 bg-garis group-hover:bg-biru transition-colors"></div>
                            @endif

                            <!-- Status Node Dot -->
                            <div class="absolute left-0 top-1 w-4 h-4 rounded-full border-2 border-white shadow-xs
                                {{ $p->status === 'masuk' ? 'bg-hijau ring-2 ring-hijau-muda' : '' }}
                                {{ $p->status === 'pulang' ? 'bg-biru ring-2 ring-biru-muda' : '' }}
                                {{ $p->status === 'sakit' ? 'bg-emas ring-2 ring-emas-muda' : '' }}
                                {{ $p->status === 'izin' && $p->approval_status === 'disetujui' ? 'bg-hijau ring-2 ring-hijau-muda' : '' }}
                                {{ $p->status === 'izin' && $p->approval_status === 'ditolak' ? 'bg-merah ring-2 ring-merah-muda' : '' }}
                                {{ $p->status === 'izin' && $p->approval_status !== 'disetujui' && $p->approval_status !== 'ditolak' ? 'bg-emas ring-2 ring-emas-muda' : '' }}">
                            </div>

                            <div class="flex items-start justify-between gap-2">
                                <div class="space-y-0.5">
                                    <h4 class="text-sm font-semibold text-teks leading-snug">
                                        {{ $p->guru->nama }}
                                    </h4>
                                    <p class="text-[11px] text-redup font-mono">
                                        {{ $p->guru->nik }}
                                    </p>
                                </div>

                                <div class="text-right shrink-0">
                                    <span @class([
                                        'inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider',
                                        'bg-hijau-muda text-hijau' => $p->status === 'masuk' || ($p->status === 'izin' && $p->approval_status === 'disetujui'),
                                        'bg-biru-muda text-biru' => $p->status === 'pulang',
                                        'bg-emas-muda text-emas-tua' => $p->status === 'sakit' || ($p->status === 'izin' && $p->approval_status !== 'disetujui' && $p->approval_status !== 'ditolak'),
                                        'bg-merah-muda text-merah' => $p->status === 'izin' && $p->approval_status === 'ditolak',
                                    ])>
                                        @if ($p->status === 'izin')
                                            @if ($p->approval_status === 'disetujui')
                                                ✓ Izin Disetujui
                                            @elseif ($p->approval_status === 'ditolak')
                                                ✕ Izin Ditolak
                                            @else
                                                ⏳ Izin Menunggu
                                            @endif
                                        @else
                                            {{ ucfirst($p->status) }}
                                        @endif
                                    </span>
                                    <span class="block text-[11px] font-medium text-redup font-mono mt-0.5">
                                        {{ \Illuminate\Support\Carbon::parse($p->waktu)->format('H:i') }} WIB
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-redup space-y-2">
                            <div class="w-12 h-12 rounded-full bg-krem text-redup flex items-center justify-center mx-auto text-xl">
                                🕒
                            </div>
                            <p class="text-sm font-semibold text-teks">Belum Ada Presensi Hari Ini</p>
                            <p class="text-xs text-redup max-w-xs mx-auto">Aktivitas kehadiran guru yang masuk atau pulang akan muncul di feed ini secara otomatis.</p>
                        </div>
                    @endforelse
                </div>

                <div class="p-4 border-t border-garis bg-krem text-center">
                    <a href="{{ route('admin.laporan.index') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-biru hover:underline">
                        <span>Buka Rekapitulasi Laporan Lengkap</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
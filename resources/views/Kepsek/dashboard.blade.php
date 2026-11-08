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

        <!-- Banner Selamat Datang (Matching Login Header Tone) -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-biru-tua via-biru to-biru-tua text-white p-6 sm:p-8 shadow-md border-b-4 border-emas">
            <!-- Subtle Watermark Logo -->
            <div class="absolute -right-8 -bottom-10 w-64 h-64 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo.png') }}" alt="Watermark Logo" class="w-full h-full object-contain">
            </div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-emas/40 text-emas-terang text-xs font-semibold tracking-wide">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-3.5 h-3.5 object-contain rounded-full">
                        <span>KEPALA SEKOLAH &middot; MODE PEMANTAUAN</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                        Selamat Datang, {{ Auth::user()->nama }} 👋
                    </h1>
                    <p class="text-sm sm:text-base text-[#cdd5ea] max-w-2xl leading-relaxed">
                        Pantau kehadiran dewan guru SMK Islam Al-Amanah Salem secara langsung hari ini.
                    </p>
                </div>

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

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            <!-- Card 1: Total Guru (Biru) -->
            <div class="bg-white rounded-xl p-5 border border-garis shadow-xs hover:shadow-md transition-all border-t-4 border-t-biru flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-redup uppercase tracking-wider">Total Dewan Guru</span>
                        <div class="w-10 h-10 rounded-lg bg-biru-muda text-biru flex items-center justify-center text-lg">👥</div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold text-biru-tua">{{ $totalGuru }}</span>
                        <span class="text-xs font-medium text-redup">Guru / Staf</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#efe8d8] text-xs text-redup">Jumlah guru terdaftar</div>
            </div>

            <!-- Card 2: Hadir Hari Ini (Hijau) -->
            <div class="bg-white rounded-xl p-5 border border-garis shadow-xs hover:shadow-md transition-all border-t-4 border-t-hijau flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-redup uppercase tracking-wider">Hadir Hari Ini</span>
                        <div class="w-10 h-10 rounded-lg bg-hijau-muda text-hijau flex items-center justify-center text-lg">✅</div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold text-hijau">{{ $hadirHariIni }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-hijau-muda text-hijau">{{ $persenHadir }}%</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#efe8d8] text-xs text-redup">Tercatat presensi masuk</div>
            </div>

            <!-- Card 3: Izin (Biru) -->
            <div class="bg-white rounded-xl p-5 border border-garis shadow-xs hover:shadow-md transition-all border-t-4 border-t-biru flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-redup uppercase tracking-wider">Izin</span>
                        <div class="w-10 h-10 rounded-lg bg-biru-muda text-biru flex items-center justify-center text-lg">📄</div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold text-biru">{{ $izinHariIni }}</span>
                        <span class="text-xs font-medium text-redup">Guru</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#efe8d8] text-xs text-redup">Mengajukan izin hari ini</div>
            </div>

            <!-- Card 4: Sakit (Emas) -->
            <div class="bg-white rounded-xl p-5 border border-garis shadow-xs hover:shadow-md transition-all border-t-4 border-t-emas flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-redup uppercase tracking-wider">Sakit</span>
                        <div class="w-10 h-10 rounded-lg bg-emas-muda text-emas-tua flex items-center justify-center text-lg">🌡️</div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold text-emas-tua">{{ $sakitHariIni }}</span>
                        <span class="text-xs font-medium text-redup">Guru</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#efe8d8] text-xs text-redup">Keterangan sakit hari ini</div>
            </div>

            <!-- Card 5: Belum Presensi (Merah) -->
            <div class="bg-white rounded-xl p-5 border border-garis shadow-xs hover:shadow-md transition-all border-t-4 border-t-merah flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-redup uppercase tracking-wider">Belum Presensi</span>
                        <div class="w-10 h-10 rounded-lg bg-merah-muda text-merah flex items-center justify-center text-lg">⏳</div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold text-merah">{{ $belumPresensiCount }}</span>
                        <span class="text-xs font-medium text-redup">Guru</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#efe8d8] text-xs text-merah font-semibold">Belum mencatat kehadiran</div>
            </div>

        </div>

        <!-- Tingkat Kehadiran Harian -->
        <div class="bg-white rounded-xl p-5 border border-garis shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-sm font-bold text-biru-tua">Tingkat Kehadiran Harian</h3>
                    <p class="text-xs text-redup">Proporsi kehadiran dewan guru berdasarkan status hari ini</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="px-2.5 py-1 rounded-full bg-hijau-muda text-hijau font-semibold">Hadir: <strong>{{ $hadirHariIni }}</strong> ({{ $persenHadir }}%)</span>
                    <span class="px-2.5 py-1 rounded-full bg-biru-muda text-biru font-semibold">Izin: <strong>{{ $izinHariIni }}</strong></span>
                    <span class="px-2.5 py-1 rounded-full bg-emas-muda text-emas-tua font-semibold">Sakit: <strong>{{ $sakitHariIni }}</strong></span>
                    <span class="px-2.5 py-1 rounded-full bg-merah-muda text-merah font-semibold">Belum: <strong>{{ $belumPresensiCount }}</strong></span>
                </div>
            </div>

            <div class="w-full h-3 rounded-full bg-[#efe8d8] overflow-hidden flex shadow-inner">
                @if ($totalGuru > 0)
                    <div style="width: {{ $persenHadir }}%" class="bg-hijau h-full" title="Hadir: {{ $hadirHariIni }}"></div>
                    <div style="width: {{ $persenIzin }}%" class="bg-biru h-full" title="Izin: {{ $izinHariIni }}"></div>
                    <div style="width: {{ $persenSakit }}%" class="bg-emas h-full" title="Sakit: {{ $sakitHariIni }}"></div>
                    <div style="width: {{ $persenBelum }}%" class="bg-garis h-full" title="Belum: {{ $belumPresensiCount }}"></div>
                @else
                    <div class="w-full bg-garis h-full"></div>
                @endif
            </div>

            <!-- Aturan presensi -->
            <div class="pt-2 border-t border-garis flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-redup">
                <span class="font-bold text-teks">Aturan Presensi:</span>
                <span class="bg-biru-muda border border-[#cbd7ee] text-biru-tua px-2.5 py-1 rounded-md font-semibold">📍 Geofence Radius {{ $pengaturan->radius }}m</span>
                <span class="bg-emas-muda border border-[#ecd9a0] text-[#7a560a] px-2.5 py-1 rounded-md font-semibold">⏰ Toleransi Masuk s/d {{ \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_toleransi)->format('H:i') }} WIB</span>
                <span class="bg-[#fdfcf9] border border-garis text-teks px-2.5 py-1 rounded-md font-semibold">🚪 Jam Pulang Minimal {{ \Illuminate\Support\Carbon::parse($pengaturan->jam_pulang_minimal)->format('H:i') }} WIB</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Ringkasan Kehadiran Guru Hari Ini -->
            <div class="lg:col-span-8 bg-white rounded-xl border border-garis shadow-xs overflow-hidden">
                <div class="p-5 border-b border-garis space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-biru-tua">Ringkasan Kehadiran Guru Hari Ini</h3>
                            <p class="text-xs text-redup">Daftar presensi seluruh dewan guru hari ini</p>
                        </div>
                        <div class="text-xs text-redup font-medium bg-krem border border-garis px-3 py-1 rounded-md">
                            Menampilkan <span class="font-bold text-biru" x-text="filteredTeachers.length"></span> dari <span class="font-bold text-teks">{{ count($ringkasanGuru) }}</span> guru
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <input type="text" x-model="search" placeholder="Cari berdasarkan nama, NIK, atau guru mapel..."
                                   class="w-full px-4 py-2.5 rounded-lg border border-garis text-xs sm:text-sm focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none placeholder:text-redup bg-[#fdfcf9]">
                        </div>

                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                            <button type="button" @click="filterStatus = 'all'"
                                    :class="filterStatus === 'all' ? 'bg-biru text-white font-bold' : 'bg-[#fdfcf9] text-redup border border-garis hover:bg-krem'"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-colors shrink-0">Semua</button>
                            <button type="button" @click="filterStatus = 'hadir'"
                                    :class="filterStatus === 'hadir' ? 'bg-hijau text-white font-bold' : 'bg-hijau-muda text-hijau hover:opacity-90'"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-colors shrink-0">Hadir</button>
                            <button type="button" @click="filterStatus = 'izin'"
                                    :class="filterStatus === 'izin' ? 'bg-biru text-white font-bold' : 'bg-biru-muda text-biru hover:opacity-90'"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-colors shrink-0">Izin</button>
                            <button type="button" @click="filterStatus = 'sakit'"
                                    :class="filterStatus === 'sakit' ? 'bg-emas text-white font-bold' : 'bg-emas-muda text-emas-tua hover:opacity-90'"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-colors shrink-0">Sakit</button>
                            <button type="button" @click="filterStatus = 'belum presensi'"
                                    :class="filterStatus === 'belum presensi' ? 'bg-merah text-white font-bold' : 'bg-merah-muda text-merah hover:opacity-90'"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-colors shrink-0">Belum</button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm divide-y divide-garis">
                        <thead class="bg-biru-muda text-biru-tua font-bold text-xs uppercase tracking-wider">
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
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-biru text-emas-terang text-xs font-bold flex items-center justify-center shrink-0 border border-emas/30">
                                                <span x-text="g.nama ? g.nama.charAt(0).toUpperCase() : 'G'"></span>
                                            </div>
                                            <div>
                                                <span class="block font-semibold text-teks text-sm leading-tight" x-text="g.nama"></span>
                                                <span class="block text-[11px] text-redup mt-0.5" x-text="g.label_mapel || (g.mata_pelajaran ? 'Guru ' + g.mata_pelajaran : 'Tenaga Pengajar')"></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-xs text-redup font-mono" x-text="g.nik"></td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-xs">
                                        <span class="inline-flex px-2.5 py-1 rounded-md bg-hijau-muda text-hijau font-medium font-mono" x-text="g.jam_masuk ? g.jam_masuk.substring(0, 5) : '-'"></span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-xs">
                                        <span class="inline-flex px-2.5 py-1 rounded-md bg-biru-muda text-biru font-medium font-mono" x-text="g.jam_pulang ? g.jam_pulang.substring(0, 5) : '-'"></span>
                                    </td>
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
                                                }" class="px-2.5 py-1 rounded-full text-xs font-semibold" x-text="g.status"></span>
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
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-garis bg-krem flex items-center justify-between text-xs text-redup">
                    <span>Data diperbarui secara real-time.</span>
                    <a href="{{ route('kepsek.laporan.index') }}" class="font-bold text-biru hover:underline">Lihat Rekap Selengkapnya →</a>
                </div>
            </div>

            <!-- Log Aktivitas Terkini (Feed Real-Time) -->
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

                <div class="p-5 space-y-4">
                    @forelse ($presensiHariIni as $p)
                        <div class="flex items-start gap-3 text-xs">
                            <span class="w-2.5 h-2.5 rounded-full mt-1 shrink-0
                                {{ $p->status === 'masuk' ? 'bg-hijau' : '' }}
                                {{ $p->status === 'pulang' ? 'bg-biru' : '' }}
                                {{ $p->status === 'sakit' ? 'bg-emas' : '' }}
                                {{ $p->status === 'izin' && $p->approval_status === 'disetujui' ? 'bg-hijau' : '' }}
                                {{ $p->status === 'izin' && $p->approval_status === 'ditolak' ? 'bg-merah' : '' }}
                                {{ $p->status === 'izin' && $p->approval_status !== 'disetujui' && $p->approval_status !== 'ditolak' ? 'bg-emas' : '' }}"></span>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-teks truncate">{{ $p->guru->nama }}</p>
                                <p class="text-redup">{{ \Illuminate\Support\Carbon::parse($p->waktu)->format('H:i') }} WIB</p>
                            </div>
                            <span @class([
                                'px-2 py-0.5 rounded text-[10px] font-bold uppercase',
                                'bg-hijau-muda text-hijau' => $p->status === 'masuk' || ($p->status === 'izin' && $p->approval_status === 'disetujui'),
                                'bg-biru-muda text-biru' => $p->status === 'pulang',
                                'bg-emas-muda text-emas-tua' => $p->status === 'sakit' || ($p->status === 'izin' && $p->approval_status !== 'disetujui' && $p->approval_status !== 'ditolak'),
                                'bg-merah-muda text-merah' => $p->status === 'izin' && $p->approval_status === 'ditolak',
                            ])>
                                @if ($p->status === 'izin')
                                    @if ($p->approval_status === 'disetujui')
                                        ✓ Disetujui
                                    @elseif ($p->approval_status === 'ditolak')
                                        ✕ Ditolak
                                    @else
                                        ⏳ Menunggu
                                    @endif
                                @else
                                    {{ $p->status }}
                                @endif
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-redup text-center py-6">Belum ada presensi hari ini.</p>
                    @endforelse
                </div>

                <div class="p-4 border-t border-garis bg-krem text-center">
                    <a href="{{ route('kepsek.log.index') }}" class="text-xs font-bold text-biru hover:underline">
                        Lihat Semua Log Presensi →
                    </a>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
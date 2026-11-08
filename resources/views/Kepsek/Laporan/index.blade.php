<x-app-layout>
    <div class="space-y-6">

        <!-- Hero Banner Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-biru-tua via-biru to-biru-tua text-white p-6 sm:p-8 shadow-xl border-b-4 border-emas">
            <!-- Subtle Watermark Logo -->
            <div class="absolute -right-8 -bottom-10 w-64 h-64 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo.png') }}" alt="Watermark Logo" class="w-full h-full object-contain">
            </div>

            <!-- Background Decorative Glow -->
            <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-16 w-56 h-56 rounded-full bg-emas/20 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-emas-terang text-xs font-semibold tracking-wide">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-3.5 h-3.5 object-contain">
                        <span>SMK ISLAM AL AMANAH SALEM</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                        Rekap & Analisis Laporan Presensi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-200 max-w-xl">
                        Tinjauan kedisiplinan guru dan rekapitulasi kehadiran pengajar untuk bahan evaluasi pimpinan.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('kepsek.laporan.pdf', ['mulai_tanggal' => $mulai, 'sampai_tanggal' => $sampai]) }}"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emas text-biru-tua hover:bg-emas/90 font-bold text-xs uppercase tracking-wider shadow-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh PDF Periode Ini</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Rentang Tanggal Card -->
        <div class="bg-white rounded-2xl border border-garis p-5 sm:p-6 shadow-xs">
            <div class="flex items-center gap-2.5 mb-4 text-teks font-bold text-sm">
                <div class="w-8 h-8 rounded-xl bg-biru-muda text-biru-tua flex items-center justify-center text-sm font-bold border border-biru/20">
                    📅
                </div>
                <div>
                    <h3 class="leading-tight text-biru-tua">Filter Periode Tanggal Laporan</h3>
                    <p class="text-xs text-redup font-normal">Pilih jangka waktu untuk menganalisis performa dan rekap kehadiran guru.</p>
                </div>
            </div>

            <form method="GET" class="flex flex-col sm:flex-row sm:items-end gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-teks uppercase tracking-wider mb-1.5">
                        Mulai Tanggal
                    </label>
                    <input type="date" name="mulai_tanggal" value="{{ $mulai }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all bg-white">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-teks uppercase tracking-wider mb-1.5">
                        Sampai Tanggal
                    </label>
                    <input type="date" name="sampai_tanggal" value="{{ $sampai }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all bg-white">
                </div>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-biru text-emas-terang font-semibold text-sm rounded-xl hover:bg-biru-tua border border-emas/40 transition-all shadow-xs shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Tampilkan & Analisis Laporan</span>
                </button>
            </form>
        </div>

        <!-- Section Title Analisis Kinerja Guru -->
        <div class="flex items-center gap-2.5 pt-2">
            <div class="w-2.5 h-6 rounded-full bg-biru"></div>
            <h3 class="font-extrabold text-biru-tua text-lg">Hasil Analisis Kedisiplinan & Kinerja Guru</h3>
            <span class="text-xs text-redup font-normal ml-auto">Periode Terpilih</span>
        </div>

        <!-- Grid 6 Kartu Analisis -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <!-- 1. Paling Cepat Datang -->
            <div class="bg-white border border-garis rounded-2xl p-5 shadow-xs relative overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 left-0 right-0 h-1 bg-hijau"></div>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-hijau-muda text-hijau text-xs font-bold border border-hijau/30">
                            🏆 PALING CEPAT DATANG
                        </span>
                        <span class="text-xs text-redup">Paling Awal</span>
                    </div>
                    <div class="space-y-2 mt-3">
                        @forelse ($palingCepat as $item)
                            <div class="flex items-center justify-between bg-krem border border-garis rounded-xl px-3 py-2 text-xs">
                                <span class="font-semibold text-teks truncate mr-2">{{ $item['nama'] }}</span>
                                <span class="font-mono font-bold text-hijau bg-hijau-muda px-2 py-0.5 rounded-md border border-hijau/20 shrink-0">
                                    {{ \Illuminate\Support\Carbon::parse($item['jam'])->format('H:i') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-redup py-3 text-center">Belum ada data kehadiran.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 2. Terlambat -->
            <div class="bg-white border border-garis rounded-2xl p-5 shadow-xs relative overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 left-0 right-0 h-1 bg-merah"></div>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-merah-muda text-merah text-xs font-bold border border-merah/30">
                            ⏰ TERLAMBAT / CHECK-IN AKHIR
                        </span>
                        <span class="text-xs text-redup">Kedisiplinan</span>
                    </div>
                    <div class="space-y-2 mt-3">
                        @forelse ($terlambat as $item)
                            <div class="flex items-center justify-between bg-krem border border-garis rounded-xl px-3 py-2 text-xs">
                                <span class="font-semibold text-teks truncate mr-2">{{ $item['nama'] }}</span>
                                <span class="font-mono font-bold text-merah bg-merah-muda px-2 py-0.5 rounded-md border border-merah/20 shrink-0">
                                    {{ $item['jumlah'] }}x Telat
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-redup py-3 text-center">Tidak ada yang terlambat.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 3. Durasi Kerja Terpendek -->
            <div class="bg-white border border-garis rounded-2xl p-5 shadow-xs relative overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 left-0 right-0 h-1 bg-emas"></div>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emas-muda text-teks text-xs font-bold border border-emas/30">
                            ⏱️ DURASI KERJA TERPENDEK
                        </span>
                        <span class="text-xs text-redup">Rerata Harian</span>
                    </div>
                    <div class="space-y-2 mt-3">
                        @forelse ($durasiTerpendek as $item)
                            <div class="flex items-center justify-between bg-krem border border-garis rounded-xl px-3 py-2 text-xs">
                                <span class="font-semibold text-teks truncate mr-2">{{ $item['nama'] }}</span>
                                <span class="font-mono font-bold text-teks bg-emas-muda px-2 py-0.5 rounded-md border border-emas/30 shrink-0">
                                    {{ $item['rata'] }} mnt/hari
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-redup py-3 text-center">Belum ada data durasi kerja.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 4. Lupa Absen Pulang -->
            <div class="bg-white border border-garis rounded-2xl p-5 shadow-xs relative overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 left-0 right-0 h-1 bg-emas"></div>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emas-muda text-teks text-xs font-bold border border-emas/30">
                            ❓ LUPA ABSEN PULANG
                        </span>
                        <span class="text-xs text-redup">Tidak Checkout</span>
                    </div>
                    <div class="space-y-2 mt-3">
                        @forelse ($lupaPulang as $item)
                            <div class="flex items-center justify-between bg-krem border border-garis rounded-xl px-3 py-2 text-xs">
                                <span class="font-semibold text-teks truncate mr-2">{{ $item['nama'] }}</span>
                                <span class="font-mono font-bold text-teks bg-emas-muda px-2 py-0.5 rounded-md border border-emas/30 shrink-0">
                                    {{ $item['jumlah'] }}x Lupa
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-redup py-3 text-center">Semua guru checkout tertib.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 5. Sering Sakit -->
            <div class="bg-white border border-garis rounded-2xl p-5 shadow-xs relative overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 left-0 right-0 h-1 bg-merah"></div>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-merah-muda text-merah text-xs font-bold border border-merah/30">
                            ➕ SERING SAKIT
                        </span>
                        <span class="text-xs text-redup">Kesehatan</span>
                    </div>
                    <div class="space-y-2 mt-3">
                        @forelse ($seringSakit as $item)
                            <div class="flex items-center justify-between bg-krem border border-garis rounded-xl px-3 py-2 text-xs">
                                <span class="font-semibold text-teks truncate mr-2">{{ $item['nama'] }}</span>
                                <span class="font-mono font-bold text-merah bg-merah-muda px-2 py-0.5 rounded-md border border-merah/20 shrink-0">
                                    {{ $item['jumlah'] }} Hari
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-redup py-3 text-center">Tidak ada catatan sakit.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 6. Sering Izin -->
            <div class="bg-white border border-garis rounded-2xl p-5 shadow-xs relative overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 left-0 right-0 h-1 bg-biru"></div>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-biru-muda text-biru-tua text-xs font-bold border border-biru/30">
                            ✉️ SERING IZIN
                        </span>
                        <span class="text-xs text-redup">Perizinan</span>
                    </div>
                    <div class="space-y-2 mt-3">
                        @forelse ($seringIzin as $item)
                            <div class="flex items-center justify-between bg-krem border border-garis rounded-xl px-3 py-2 text-xs">
                                <span class="font-semibold text-teks truncate mr-2">{{ $item['nama'] }}</span>
                                <span class="font-mono font-bold text-biru-tua bg-biru-muda px-2 py-0.5 rounded-md border border-biru/20 shrink-0">
                                    {{ $item['jumlah'] }} Hari
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-redup py-3 text-center">Tidak ada catatan izin.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <!-- Tabel Hasil Rekapitulasi Card -->
        <div class="bg-white rounded-2xl border border-garis shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-garis flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-biru text-emas-terang flex items-center justify-center text-sm font-bold border border-emas/30 shadow-xs">
                        📊
                    </div>
                    <div>
                        <h3 class="font-bold text-biru-tua text-base leading-tight">Hasil Rekapitulasi Kehadiran Guru</h3>
                        <p class="text-xs text-redup mt-0.5">Total presensi masuk, sakit, dan izin per guru dalam periode ini.</p>
                    </div>
                </div>

                <a href="{{ route('kepsek.laporan.pdf', ['mulai_tanggal' => $mulai, 'sampai_tanggal' => $sampai]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-biru text-emas-terang text-xs font-semibold rounded-xl hover:bg-biru-tua border border-emas/40 transition-all shadow-xs self-start sm:self-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Cetak PDF</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-biru-muda border-b border-garis text-biru-tua text-xs font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama Guru</th>
                            <th class="px-6 py-4 text-center">Hadir (Masuk)</th>
                            <th class="px-6 py-4 text-center">Sakit</th>
                            <th class="px-6 py-4 text-center">Izin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-garis">
                        @forelse ($rekap as $r)
                            <tr class="hover:bg-biru-muda/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-biru-tua text-emas-terang font-bold text-xs flex items-center justify-center shrink-0 border border-emas/30 shadow-xs">
                                            {{ strtoupper(substr($r['nama'], 0, 2)) }}
                                        </div>
                                        <span class="font-semibold text-teks">{{ $r['nama'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-hijau-muda text-hijau font-mono text-xs font-bold border border-hijau/20">
                                        {{ $r['hadir'] }} kali
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-merah-muda text-merah font-mono text-xs font-bold border border-merah/20">
                                        {{ $r['sakit'] }} kali
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emas-muda text-teks font-mono text-xs font-bold border border-emas/20">
                                        {{ $r['izin'] }} kali
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-redup text-xs">
                                    Belum ada data rekapitulasi pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
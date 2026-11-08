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
                        <span>SMK ISLAM AL AMANAH SALEM - BREBES</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                        Rekap & Analisis Laporan Presensi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-200 max-w-xl">
                        Analisis kedisiplinan guru, ringkasan rekapitulasi kehadiran, log presensi real-time, dan pengaturan titik koordinat geofencing.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.laporan.pdf', ['mulai_tanggal' => $mulai, 'sampai_tanggal' => $sampai]) }}"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emas text-biru-tua hover:bg-emas/90 font-bold text-xs uppercase tracking-wider shadow-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh PDF Periode Ini</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Session Status Alert -->
        @if (session('status'))
            <div class="flex items-center gap-3 p-4 rounded-2xl bg-biru-muda border border-biru/20 text-biru-tua text-sm shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-biru text-emas-terang flex items-center justify-center shrink-0 text-sm font-bold shadow-xs">
                    ✓
                </div>
                <div class="flex-1 font-medium">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <!-- Filter Rentang Tanggal Card -->
        <div class="bg-white rounded-2xl border border-garis p-5 sm:p-6 shadow-xs">
            <div class="flex items-center gap-2.5 mb-4 text-teks font-bold text-sm">
                <div class="w-8 h-8 rounded-xl bg-biru-muda text-biru-tua flex items-center justify-center text-sm font-bold border border-biru/20">
                    📅
                </div>
                <div>
                    <h3 class="leading-tight text-biru-tua">Filter Periode Tanggal Laporan</h3>
                    <p class="text-xs text-redup font-normal">Pilih jangka waktu untuk menganalisis performa dan rekap kehadiran.</p>
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

        <!-- Standar Jam Kerja & Lokasi Presensi Card -->
        <div class="bg-white rounded-2xl border border-garis p-5 sm:p-6 shadow-xs" x-data="pengaturanLokasi()">
            <div class="flex items-center justify-between pb-4 border-b border-garis mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-biru-tua text-emas-terang flex items-center justify-center text-sm font-bold border border-emas/30 shadow-xs">
                        ⚙️
                    </div>
                    <div>
                        <h3 class="font-bold text-biru-tua text-base leading-tight">Standar Jam Kerja & Titik Koordinat Presensi</h3>
                        <p class="text-xs text-redup mt-0.5">Konfigurasi batas toleransi keterlambatan dan radius geofencing sekolah.</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.laporan.jam-kerja') }}" class="space-y-5">
                @csrf

                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-merah-muda border border-merah/20 text-merah text-xs space-y-1">
                        <p class="font-bold flex items-center gap-1.5">
                            <span>⚠️</span> Terjadi kesalahan pengisian form:
                        </p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <!-- Aturan Jam Operasional Presensi Masuk -->
                    <div class="p-4 sm:p-5 bg-krem border border-garis rounded-2xl space-y-3.5">
                        <div class="flex items-center gap-2 text-biru-tua font-bold text-xs uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-lg bg-hijau-muda text-hijau flex items-center justify-center text-xs">☀️</span>
                            <span>Operasional Presensi Masuk</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-teks">Buka Masuk</label>
                                <input type="time" name="jam_masuk_mulai" required
                                    value="{{ old('jam_masuk_mulai', \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_mulai ?? '06:00:00')->format('H:i')) }}"
                                    class="w-full px-3 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-1 focus:ring-biru bg-white text-teks">
                                <span class="text-[10px] text-redup block">Mulai absen</span>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-teks">Toleransi Telat</label>
                                <input type="time" name="jam_masuk_toleransi" required
                                    value="{{ old('jam_masuk_toleransi', \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_toleransi)->format('H:i')) }}"
                                    class="w-full px-3 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-1 focus:ring-biru bg-white text-teks">
                                <span class="text-[10px] text-redup block">Batas tepat waktu</span>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-teks">Tutup Masuk</label>
                                <input type="time" name="jam_masuk_selesai" required
                                    value="{{ old('jam_masuk_selesai', \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_selesai ?? '12:00:00')->format('H:i')) }}"
                                    class="w-full px-3 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-1 focus:ring-biru bg-white text-teks">
                                <span class="text-[10px] text-redup block">Batas akhir masuk</span>
                            </div>
                        </div>
                    </div>

                    <!-- Aturan Jam Operasional Presensi Pulang -->
                    <div class="p-4 sm:p-5 bg-krem border border-garis rounded-2xl space-y-3.5">
                        <div class="flex items-center gap-2 text-biru-tua font-bold text-xs uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-lg bg-biru-muda text-biru-tua flex items-center justify-center text-xs">🌙</span>
                            <span>Operasional Presensi Pulang</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-teks">Minimal Jam Pulang</label>
                                <input type="time" name="jam_pulang_minimal" required
                                    value="{{ old('jam_pulang_minimal', \Illuminate\Support\Carbon::parse($pengaturan->jam_pulang_minimal)->format('H:i')) }}"
                                    class="w-full px-3 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-1 focus:ring-biru bg-white text-teks">
                                <span class="text-[10px] text-redup block">Mulai absen pulang</span>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-teks">Batas Tutup Pulang</label>
                                <input type="time" name="jam_pulang_selesai" required
                                    value="{{ old('jam_pulang_selesai', \Illuminate\Support\Carbon::parse($pengaturan->jam_pulang_selesai ?? '19:00:00')->format('H:i')) }}"
                                    class="w-full px-3 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-1 focus:ring-biru bg-white text-teks">
                                <span class="text-[10px] text-redup block">Batas akhir pulang</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Geofencing & Koordinat Sekolah -->
                <div class="p-4 sm:p-5 bg-krem border border-garis rounded-2xl space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2 text-biru-tua font-bold text-xs uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-lg bg-emas-muda text-emas flex items-center justify-center text-xs">📍</span>
                            <span>Koordinat Lokasi & Radius Sekolah</span>
                        </div>

                        <button type="button" @click="ambilLokasiSaatIni()"
                            class="inline-flex items-center gap-2 text-xs px-3.5 py-1.5 bg-biru text-emas-terang rounded-xl hover:bg-biru-tua border border-emas/30 transition-all font-semibold shadow-xs self-start sm:self-auto"
                            :disabled="mencari">
                            <svg x-show="!mencari" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg x-show="mencari" x-cloak class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-show="!mencari">Gunakan Lokasi GPS Saat Ini</span>
                            <span x-show="mencari" x-cloak>Mencari Titik GPS...</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-teks">Latitude</label>
                            <input type="text" name="latitude_sekolah" x-model="latitude"
                                value="{{ $pengaturan->latitude_sekolah }}"
                                class="w-full px-3 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-1 focus:ring-biru bg-white text-teks">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-teks">Longitude</label>
                            <input type="text" name="longitude_sekolah" x-model="longitude"
                                value="{{ $pengaturan->longitude_sekolah }}"
                                class="w-full px-3 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-1 focus:ring-biru bg-white text-teks">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-teks">Radius Validasi (meter)</label>
                            <input type="number" name="radius" value="{{ $pengaturan->radius }}"
                                class="w-full px-3 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-1 focus:ring-biru bg-white text-teks">
                        </div>
                    </div>

                    <p x-show="pesan" x-text="pesan" class="text-xs text-biru-tua font-medium bg-biru-muda border border-biru/20 p-2.5 rounded-xl"></p>
                </div>

                <!-- Integrasi WhatsApp Kepala Sekolah (Persetujuan Izin) -->
                <div class="p-4 sm:p-5 bg-krem border border-garis rounded-2xl space-y-3.5">
                    <div class="flex items-center gap-2 text-biru-tua font-bold text-xs uppercase tracking-wider">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-[#25D366] flex items-center justify-center text-xs">💬</span>
                        <span>Nomor WhatsApp Kepala Sekolah (Persetujuan Izin)</span>
                    </div>
                    <div class="space-y-1 max-w-md">
                        <label class="block text-xs font-semibold text-teks">Nomor WhatsApp Kepala Sekolah</label>
                        <input type="text" name="no_wa_kepsek" value="{{ old('no_wa_kepsek', $pengaturan->no_wa_kepsek) }}"
                            placeholder="Contoh: 08123456789 atau 628123456789"
                            class="w-full px-3 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-1 focus:ring-biru bg-white text-teks">
                        <p class="text-[11px] text-redup">Nomor tujuan pesan WhatsApp otomatis saat guru mengajukan izin tidak hadir.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-biru text-emas-terang font-semibold text-xs rounded-xl hover:bg-biru-tua border border-emas/40 shadow-xs transition-all uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Standar & Lokasi</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Awal Tahun Ajaran Baru Card -->
        <!-- Awal Tahun Ajaran Baru Card (Arsip & Reset) -->
        <div x-data="{ modalReset: false, tahunAjaran: '{{ $saranTahun ?? date('Y') . '/' . (date('Y') + 1) }}', semester: '{{ $saranSemester ?? 'semua' }}' }" class="bg-white rounded-2xl border border-garis p-5 sm:p-6 shadow-xs">
            <div class="flex items-start gap-4 sm:gap-5">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0 mt-0.5 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <div class="flex-1 space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h4 class="font-bold text-teks text-base sm:text-lg leading-tight">Awal Tahun Ajaran Baru?</h4>
                            <p class="text-xs sm:text-sm text-redup mt-1 leading-relaxed">
                                Simpan seluruh data presensi aktif ke dalam <strong>Arsip Presensi</strong>, lalu kosongkan riwayat untuk memulai siklus presensi baru dari angka 0.
                            </p>
                        </div>
                        <a href="{{ route('admin.arsip.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-biru hover:text-biru-tua hover:underline shrink-0">
                            <span>Lihat Arsip Presensi</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <button type="button" @click="modalReset = true"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-700 hover:bg-red-800 active:bg-red-900 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"/>
                            </svg>
                            <span>Arsipkan & Mulai Tahun Ajaran Baru</span>
                        </button>
                        <span class="text-xs text-redup">
                            (Saat ini ada <strong>{{ number_format($totalPresensiAktif ?? 0, 0, ',', '.') }}</strong> catatan presensi aktif)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Modal Konfirmasi Arsip & Kosongkan Presensi -->
            <div x-show="modalReset" x-cloak
                class="fixed inset-0 bg-biru-tua/70 backdrop-blur-xs flex items-center justify-center z-50 p-4"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div @click.away="modalReset = false"
                    class="bg-white rounded-3xl shadow-2xl border border-garis w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-200">

                    <!-- Modal Header -->
                    <div class="p-6 bg-gradient-to-r from-biru to-biru-tua text-white relative border-b-2 border-emas">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-base text-white">Arsip Presensi Tahun Ajaran</h3>
                                <p class="text-xs text-blue-100">Simpan riwayat ke arsip & reset presensi aktif</p>
                            </div>
                        </div>
                        <button type="button" @click="modalReset = false"
                            class="absolute top-5 right-5 text-white/80 hover:text-white transition-colors text-lg">
                            ✕
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form method="POST" action="{{ route('admin.laporan.kosongkan') }}" class="p-6 space-y-4">
                        @csrf
                        
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-2xl text-xs text-blue-900 space-y-1.5 leading-relaxed">
                            <p class="font-bold flex items-center gap-1.5 text-blue-950">
                                <span>📦</span> <strong>{{ number_format($totalPresensiAktif ?? 0, 0, ',', '.') }} data presensi</strong> akan diamankan ke menu Arsip Presensi.
                            </p>
                            <p class="text-blue-800">
                                Setelah diarsipkan, tabel presensi aktif akan dimulai kembali dari <strong>angka 0</strong> untuk tahun ajaran baru. Anda tetap dapat mencari dan mencetak laporan tahun ini kapan saja di menu <strong>Arsip Presensi</strong>.
                            </p>
                        </div>

                        <!-- Input Tahun Ajaran -->
                        <div>
                            <label class="block text-xs font-bold text-teks mb-1">
                                Tahun Ajaran <span class="text-merah">*</span>
                            </label>
                            <input type="text" name="tahun_ajaran" x-model="tahunAjaran" required
                                placeholder="Contoh: 2024/2025"
                                class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-garis focus:border-biru focus:ring-1 focus:ring-biru font-semibold">
                            <p class="text-[11px] text-redup mt-1">Masukkan tahun ajaran, misal: 2024/2025, 2025/2026</p>
                        </div>

                        <!-- Pilihan Semester -->
                        <div>
                            <label class="block text-xs font-bold text-teks mb-1.5">
                                Periode / Semester <span class="text-merah">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-2">
                                <label :class="semester === 'semua' ? 'border-biru bg-blue-50/70 text-biru-tua font-bold' : 'border-garis bg-white text-teks'"
                                    class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer text-xs transition-all">
                                    <input type="radio" name="semester" value="semua" x-model="semester" class="text-biru focus:ring-biru">
                                    <span>1 Tahun Penuh</span>
                                </label>
                                <label :class="semester === 'ganjil' ? 'border-biru bg-blue-50/70 text-biru-tua font-bold' : 'border-garis bg-white text-teks'"
                                    class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer text-xs transition-all">
                                    <input type="radio" name="semester" value="ganjil" x-model="semester" class="text-biru focus:ring-biru">
                                    <span>Ganjil</span>
                                </label>
                                <label :class="semester === 'genap' ? 'border-biru bg-blue-50/70 text-biru-tua font-bold' : 'border-garis bg-white text-teks'"
                                    class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer text-xs transition-all">
                                    <input type="radio" name="semester" value="genap" x-model="semester" class="text-biru focus:ring-biru">
                                    <span>Genap</span>
                                </label>
                            </div>
                        </div>

                        <!-- Catatan Tambahan (Opsional) -->
                        <div>
                            <label class="block text-xs font-bold text-teks mb-1">
                                Catatan / Keterangan Arsip <span class="text-redup font-normal">(Opsional)</span>
                            </label>
                            <textarea name="keterangan" rows="2"
                                placeholder="Tambahkan catatan khusus untuk arsip ini jika ada..."
                                class="w-full text-xs px-3.5 py-2 rounded-xl border border-garis focus:border-biru focus:ring-1 focus:ring-biru"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-garis">
                            <button type="button" @click="modalReset = false"
                                class="px-4 py-2.5 text-xs font-semibold text-redup hover:bg-krem rounded-xl transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin mengarsipkan seluruh data presensi saat ini dan mengosongkan presensi aktif untuk tahun ajaran baru?')"
                                class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-red-700 hover:bg-red-800 text-white text-xs font-semibold rounded-xl shadow-xs transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                <span>Arsipkan & Kosongkan Presensi</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                        <h3 class="font-bold text-biru-tua text-base leading-tight">Rekapitulasi Kehadiran Guru</h3>
                        <p class="text-xs text-redup mt-0.5">Total presensi masuk, sakit, dan izin per guru dalam periode ini.</p>
                    </div>
                </div>

                <a href="{{ route('admin.laporan.pdf', ['mulai_tanggal' => $mulai, 'sampai_tanggal' => $sampai]) }}"
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

        <!-- Tabel Log Riwayat Presensi Card -->
        <div class="bg-white rounded-2xl border border-garis shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-garis flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-biru text-emas-terang flex items-center justify-center text-sm font-bold border border-emas/30 shadow-xs">
                        🕒
                    </div>
                    <div>
                        <h3 class="font-bold text-biru-tua text-base leading-tight">Log Riwayat Harian Presensi</h3>
                        <p class="text-xs text-redup mt-0.5">Catatan riwayat harian dewan guru dengan jam masuk, jam pulang, dan koordinat GPS.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-biru-muda border-b border-garis text-biru-tua text-xs font-bold uppercase tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3.5">Tanggal</th>
                            <th class="px-6 py-3.5">Nama Guru</th>
                            <th class="px-6 py-3.5 text-center">Jam Masuk</th>
                            <th class="px-6 py-3.5 text-center">Jam Pulang</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-garis">
                        @forelse ($logHarian as $log)
                            <tr class="hover:bg-biru-muda/30 transition-colors">
                                <td class="px-6 py-3.5 text-xs text-redup font-mono">
                                    {{ $log->tanggal->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-3.5 font-semibold text-teks">
                                    {{ $log->nama }}
                                </td>
                                <td class="px-6 py-3.5 text-center font-mono text-xs">
                                    @if ($log->jam_masuk)
                                        <span class="inline-flex items-center gap-1 font-bold text-hijau bg-hijau-muda px-2 py-0.5 rounded-md border border-hijau/20">
                                            <span>{{ $log->jam_masuk }}</span>
                                            @if ($log->lat_masuk && $log->long_masuk)
                                                <a href="https://maps.google.com/?q={{ $log->lat_masuk }},{{ $log->long_masuk }}" target="_blank"
                                                   title="GPS Masuk: {{ number_format((float)$log->lat_masuk, 5) }}, {{ number_format((float)$log->long_masuk, 5) }}"
                                                   class="hover:opacity-75 transition-opacity" onclick="event.stopPropagation()">
                                                    📍
                                                </a>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-redup font-semibold">-- : --</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-center font-mono text-xs">
                                    @if ($log->jam_pulang)
                                        <span class="inline-flex items-center gap-1 font-bold text-[#ea580c] bg-orange-50 px-2 py-0.5 rounded-md border border-orange-200">
                                            <span>{{ $log->jam_pulang }}</span>
                                            @if ($log->lat_pulang && $log->long_pulang)
                                                <a href="https://maps.google.com/?q={{ $log->lat_pulang }},{{ $log->long_pulang }}" target="_blank"
                                                   title="GPS Pulang: {{ number_format((float)$log->lat_pulang, 5) }}, {{ number_format((float)$log->long_pulang, 5) }}"
                                                   class="hover:opacity-75 transition-opacity" onclick="event.stopPropagation()">
                                                    📍
                                                </a>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-redup font-semibold">-- : --</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    @php
                                        $badgeStyles = [
                                            'Hadir' => 'bg-hijau-muda text-hijau border-hijau/30',
                                            'Izin' => 'bg-biru-muda text-biru-tua border-biru/30',
                                            'Sakit' => 'bg-emas-muda text-emas-tua border-emas/30',
                                        ];
                                    @endphp

                                    @if ($log->status === 'Izin')
                                        <div class="inline-flex flex-col items-center gap-1">
                                            @if ($log->approval_status === 'disetujui')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border bg-hijau-muda text-hijau border-hijau/30">
                                                    ✓ Izin (Disetujui Kepala Sekolah)
                                                </span>
                                            @elseif ($log->approval_status === 'ditolak')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border bg-merah-muda text-merah border-merah/30">
                                                    ✕ Izin (Ditolak Kepala Sekolah)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border bg-emas-muda text-emas-tua border-emas/30">
                                                    ⏳ Izin (Menunggu Persetujuan)
                                                </span>
                                                @if (!empty($log->token_izin))
                                                    <a href="{{ route('izin.approval', ['token' => $log->token_izin]) }}" target="_blank"
                                                       class="text-[10px] text-biru hover:underline font-semibold">
                                                        Tinjau / Setujui →
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeStyles[$log->status] ?? 'bg-krem text-teks border-garis' }}">
                                            {{ $log->status }}
                                        </span>
                                    @endif

                                    @if ($log->keterangan)
                                        <p class="text-[10px] text-redup mt-0.5 italic">({{ $log->keterangan }})</p>
                                    @endif
                                    @if (!empty($log->bukti_sakit))
                                        <div class="mt-1">
                                            <a href="{{ asset('storage/' . $log->bukti_sakit) }}" target="_blank"
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-merah-muda text-merah border border-merah/20 hover:bg-merah/15 font-semibold text-[10px] transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>Bukti Foto</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (!empty($log->bukti_izin))
                                        <div class="mt-1">
                                            <a href="{{ asset('storage/' . $log->bukti_izin) }}" target="_blank"
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-biru-muda text-biru border border-biru/20 hover:bg-biru/15 font-semibold text-[10px] transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>Bukti Izin</span>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-redup text-xs">
                                    Belum ada data riwayat presensi yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        function pengaturanLokasi() {
            return {
                latitude: '{{ $pengaturan->latitude_sekolah }}',
                longitude: '{{ $pengaturan->longitude_sekolah }}',
                mencari: false,
                pesan: '',

                ambilLokasiSaatIni() {
                    if (! navigator.geolocation) {
                        alert('Browser Anda tidak mendukung fitur geolokasi GPS.');
                        return;
                    }

                    this.mencari = true;
                    this.pesan = '';

                    navigator.geolocation.getCurrentPosition((posisi) => {
                        this.latitude = posisi.coords.latitude.toFixed(7);
                        this.longitude = posisi.coords.longitude.toFixed(7);
                        this.mencari = false;
                        this.pesan = '✓ Lokasi koordinat berhasil diperbarui dari GPS perangkat. Jangan lupa klik tombol "Simpan Standar & Lokasi" di bawah.';
                    }, () => {
                        this.mencari = false;
                        alert('Gagal mengambil titik lokasi GPS. Pastikan izin lokasi diaktifkan pada browser perangkat Anda.');
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000
                    });
                }
            }
        }
    </script>
</x-app-layout>
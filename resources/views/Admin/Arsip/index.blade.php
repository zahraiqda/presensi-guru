<x-app-layout>
    <div class="space-y-6">

        <!-- Hero Banner Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-biru-tua via-biru to-biru-tua text-white p-6 sm:p-8 shadow-xl border-b-4 border-emas">
            <!-- Subtle Watermark Logo -->
            <div class="absolute -right-8 -bottom-10 w-64 h-64 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo.png') }}" alt="Watermark Logo" class="w-full h-full object-contain">
            </div>

            <!-- Decorative Glow -->
            <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-16 w-56 h-56 rounded-full bg-emas/20 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-emas-terang text-xs font-semibold tracking-wide">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-3.5 h-3.5 object-contain">
                        <span>SMK ISLAM AL AMANAH SALEM</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                        Arsip Presensi Guru
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-200 max-w-xl">
                        Kumpulan seluruh riwayat presensi dewan guru yang telah diarsipkan berdasarkan Tahun Ajaran dan Semester. Data tersimpan aman dan dapat ditelusuri kapan saja.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="px-5 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center shadow-inner">
                        <p class="text-[11px] text-slate-200 font-medium uppercase tracking-wider">Tahun Diarsipkan</p>
                        <p class="text-xl sm:text-2xl font-black text-emas-terang">{{ $totalArsip }}</p>
                    </div>
                    <div class="px-5 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center shadow-inner">
                        <p class="text-[11px] text-slate-200 font-medium uppercase tracking-wider">Total Catatan</p>
                        <p class="text-xl sm:text-2xl font-black text-emas-terang">{{ number_format($totalPresensiDiarsipkan, 0, ',', '.') }}</p>
                    </div>
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

        @if (session('error'))
            <div class="flex items-center gap-3 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0 text-sm font-bold shadow-xs">
                    ✕
                </div>
                <div class="flex-1 font-medium">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-2xl border border-garis p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form method="GET" class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-redup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari Tahun Ajaran, Semester, atau catatan..."
                    class="w-full pl-10 pr-24 py-2.5 text-xs rounded-xl border border-garis focus:border-biru focus:ring-1 focus:ring-biru placeholder-redup">
                @if ($q)
                    <a href="{{ route($isAdmin ? 'admin.arsip.index' : 'kepsek.arsip.index') }}"
                        class="absolute inset-y-0 right-14 pr-2 flex items-center text-xs text-redup hover:text-merah">
                        Reset
                    </a>
                @endif
                <button type="submit"
                    class="absolute inset-y-1.5 right-1.5 px-3 bg-biru hover:bg-biru-tua text-emas-terang text-xs font-semibold rounded-lg transition-colors">
                    Cari
                </button>
            </form>

            <div class="flex items-center gap-2">
                @if ($isAdmin)
                    <a href="{{ route('admin.laporan.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-krem hover:bg-garis/70 text-teks font-semibold text-xs rounded-xl border border-garis transition-colors">
                        <svg class="w-4 h-4 text-biru" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Ke Rekap Laporan Aktif</span>
                    </a>
                @else
                    <a href="{{ route('kepsek.laporan.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-krem hover:bg-garis/70 text-teks font-semibold text-xs rounded-xl border border-garis transition-colors">
                        <svg class="w-4 h-4 text-biru" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Ke Laporan Aktif</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Daftar Arsip Tahun Ajaran Grid -->
        @if ($arsipList->isEmpty())
            <div class="bg-white rounded-3xl border border-garis p-12 text-center shadow-xs space-y-4">
                <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 border border-blue-100 text-biru flex items-center justify-center text-2xl shadow-inner">
                    📦
                </div>
                <div class="max-w-md mx-auto space-y-1">
                    <h3 class="font-bold text-lg text-teks">Belum Ada Arsip Presensi</h3>
                    <p class="text-xs text-redup leading-relaxed">
                        @if ($q)
                            Tidak ditemukan arsip dengan kata kunci "{{ $q }}". Silakan coba kata kunci lain.
                        @else
                            Arsip tahun ajaran akan otomatis tercipta ketika Admin menggunakan fitur <strong>"Arsipkan & Mulai Tahun Ajaran Baru"</strong> di halaman Rekap Laporan.
                        @endif
                    </p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($arsipList as $item)
                    <div class="bg-white rounded-2xl border border-garis p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                        
                        <div>
                            <!-- Header Card: Badge Semester & Tanggal Arsip -->
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold border
                                    {{ $item->semester === 'ganjil' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($item->semester === 'genap' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-blue-50 text-biru border-blue-200') }}">
                                    <span>🗓️</span>
                                    <span>{{ $item->semester_label }}</span>
                                </span>

                                <span class="text-[11px] text-redup">
                                    {{ $item->created_at->isoFormat('D MMM Y') }}
                                </span>
                            </div>

                            <!-- Title Tahun Ajaran -->
                            <h3 class="text-lg font-extrabold text-teks group-hover:text-biru transition-colors leading-tight">
                                {{ $item->nama_label }}
                            </h3>

                            <!-- Rentang Tanggal -->
                            <p class="text-xs text-redup mt-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-redup/80 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Periode: <strong>{{ $item->periode_teks }}</strong></span>
                            </p>

                            @if ($item->keterangan)
                                <div class="mt-2.5 p-2.5 rounded-xl bg-krem/70 border border-garis/60 text-[11px] text-teks italic">
                                    "{{ $item->keterangan }}"
                                </div>
                            @endif

                            <!-- Mini Statistik 4 Grid -->
                            <div class="grid grid-cols-4 gap-1.5 mt-4 pt-3 border-t border-garis/60 text-center">
                                <div class="p-2 rounded-xl bg-blue-50/50">
                                    <p class="text-[10px] text-redup font-semibold">Total</p>
                                    <p class="text-xs font-extrabold text-biru">{{ number_format($item->total_presensi, 0, ',', '.') }}</p>
                                </div>
                                <div class="p-2 rounded-xl bg-emerald-50/50">
                                    <p class="text-[10px] text-emerald-700 font-semibold">Hadir</p>
                                    <p class="text-xs font-extrabold text-emerald-700">{{ number_format($item->total_hadir, 0, ',', '.') }}</p>
                                </div>
                                <div class="p-2 rounded-xl bg-amber-50/50">
                                    <p class="text-[10px] text-amber-700 font-semibold">Lambat</p>
                                    <p class="text-xs font-extrabold text-amber-700">{{ number_format($item->total_terlambat, 0, ',', '.') }}</p>
                                </div>
                                <div class="p-2 rounded-xl bg-purple-50/50">
                                    <p class="text-[10px] text-purple-700 font-semibold">Izin/Skt</p>
                                    <p class="text-xs font-extrabold text-purple-700">{{ number_format($item->total_izin + $item->total_sakit, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-5 pt-3 border-t border-garis flex items-center justify-between gap-2">
                            <a href="{{ route($isAdmin ? 'admin.arsip.show' : 'kepsek.arsip.show', $item->id) }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-biru hover:bg-biru-tua text-emas-terang text-xs font-semibold rounded-xl shadow-xs transition-colors">
                                <span>Lihat Riwayat & Detail</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                            <div class="flex items-center gap-1">
                                <a href="{{ route($isAdmin ? 'admin.arsip.pdf' : 'kepsek.arsip.pdf', $item->id) }}"
                                    title="Unduh Rekap PDF"
                                    class="p-2 text-redup hover:text-biru hover:bg-blue-50 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>

                                @if ($isAdmin)
                                    <form method="POST" action="{{ route('admin.arsip.destroy', $item->id) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip {{ $item->nama_label }}? Data yang dihapus tidak dapat dipulihkan!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Arsip"
                                            class="p-2 text-redup hover:text-merah hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="pt-2">
                {{ $arsipList->links() }}
            </div>
        @endif

    </div>
</x-app-layout>

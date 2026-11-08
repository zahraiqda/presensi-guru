<x-app-layout>
    <div x-data="{ modalRestore: false, previewFoto: null, previewJudul: '' }" class="space-y-6">

        <!-- Hero Banner Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-biru-tua via-biru to-biru-tua text-white p-6 sm:p-8 shadow-xl border-b-4 border-emas">
            <!-- Subtle Watermark Logo -->
            <div class="absolute -right-8 -bottom-10 w-64 h-64 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo.png') }}" alt="Watermark Logo" class="w-full h-full object-contain">
            </div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route($isAdmin ? 'admin.arsip.index' : 'kepsek.arsip.index') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-emas-terang text-xs font-semibold tracking-wide transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Kembali ke Daftar Arsip</span>
                        </a>

                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/20 text-white">
                            {{ $arsip->semester_label }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                        {{ $arsip->nama_label }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-200 flex items-center gap-2">
                        <span>📅 Periode: <strong>{{ $arsip->periode_teks }}</strong></span>
                        @if ($arsip->keterangan)
                            <span class="text-slate-300">| "{{ $arsip->keterangan }}"</span>
                        @endif
                    </p>
                </div>

                <!-- Action Buttons: PDF & Restore -->
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route($isAdmin ? 'admin.arsip.pdf' : 'kepsek.arsip.pdf', $arsip->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emas hover:bg-emas/90 text-biru-tua font-bold text-xs rounded-xl shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Unduh Laporan PDF</span>
                    </a>

                    @if ($isAdmin)
                        <button type="button" @click="modalRestore = true"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 border border-white/25 text-white font-semibold text-xs rounded-xl backdrop-blur-xs transition-all">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Pulihkan ke Presensi Aktif</span>
                        </button>
                    @endif
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

        <!-- 5 Kartu Ringkasan Statistik -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
            <div class="bg-white border border-garis rounded-2xl p-4 shadow-xs">
                <p class="text-[11px] font-semibold text-redup uppercase tracking-wider">Total Catatan</p>
                <p class="text-2xl font-black text-teks mt-1">{{ number_format($arsip->total_presensi, 0, ',', '.') }}</p>
                <p class="text-[11px] text-redup mt-1">Presensi diarsipkan</p>
            </div>

            <div class="bg-white border border-garis rounded-2xl p-4 shadow-xs">
                <p class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider">Hadir (Masuk/Plg)</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">{{ number_format($arsip->total_hadir, 0, ',', '.') }}</p>
                <p class="text-[11px] text-emerald-600 mt-1">Catatan Kehadiran</p>
            </div>

            <div class="bg-white border border-garis rounded-2xl p-4 shadow-xs">
                <p class="text-[11px] font-semibold text-amber-700 uppercase tracking-wider">Terlambat Masuk</p>
                <p class="text-2xl font-black text-amber-700 mt-1">{{ number_format($arsip->total_terlambat, 0, ',', '.') }}</p>
                <p class="text-[11px] text-amber-600 mt-1">Lewat batas toleransi</p>
            </div>

            <div class="bg-white border border-garis rounded-2xl p-4 shadow-xs">
                <p class="text-[11px] font-semibold text-blue-700 uppercase tracking-wider">Izin</p>
                <p class="text-2xl font-black text-blue-700 mt-1">{{ number_format($arsip->total_izin, 0, ',', '.') }}</p>
                <p class="text-[11px] text-blue-600 mt-1">Pengajuan Izin</p>
            </div>

            <div class="bg-white border border-garis rounded-2xl p-4 shadow-xs col-span-2 sm:col-span-1">
                <p class="text-[11px] font-semibold text-purple-700 uppercase tracking-wider">Sakit</p>
                <p class="text-2xl font-black text-purple-700 mt-1">{{ number_format($arsip->total_sakit, 0, ',', '.') }}</p>
                <p class="text-[11px] text-purple-600 mt-1">Surat Sakit</p>
            </div>
        </div>

        <!-- Rekap Ringkas Kehadiran Per Guru (Accordion/Collapsible) -->
        <div x-data="{ openRekap: false }" class="bg-white rounded-2xl border border-garis overflow-hidden shadow-xs">
            <button type="button" @click="openRekap = !openRekap"
                class="w-full p-4 sm:p-5 flex items-center justify-between hover:bg-krem/40 transition-colors text-left">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-biru-muda text-biru flex items-center justify-center font-bold text-sm">
                        👥
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-teks">Ringkasan Kehadiran per Guru</h4>
                        <p class="text-xs text-redup">Total {{ $rekapGuru->count() }} dewan guru tercatat pada arsip tahun ini</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-biru">
                    <span x-text="openRekap ? 'Tutup Ringkasan' : 'Lihat Ringkasan Guru'"></span>
                    <svg class="w-4 h-4 transform transition-transform" :class="openRekap ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <div x-show="openRekap" x-cloak class="p-5 border-t border-garis bg-krem/20">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($rekapGuru as $rg)
                        <div class="bg-white p-3.5 rounded-xl border border-garis/80 text-xs space-y-1.5 shadow-2xs">
                            <p class="font-bold text-teks truncate">{{ $rg->nama_guru }}</p>
                            <p class="text-[11px] text-redup">
                                {{ $rg->mata_pelajaran ? 'Guru ' . $rg->mata_pelajaran : ($rg->nip_guru ? 'NIP: ' . $rg->nip_guru : 'Dewan Guru') }}
                            </p>
                            <div class="flex items-center gap-3 pt-1 text-[11px]">
                                <span class="text-emerald-700 font-bold">Hadir: {{ $rg->hadir }}</span>
                                <span class="text-blue-700 font-semibold">Izin: {{ $rg->izin }}</span>
                                <span class="text-purple-700 font-semibold">Sakit: {{ $rg->sakit }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Filter Pencarian Riwayat Presensi -->
        <div class="bg-white rounded-2xl border border-garis p-5 shadow-xs space-y-4">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Search Guru -->
                <div>
                    <label class="block text-[11px] font-bold text-redup uppercase tracking-wider mb-1">Cari Guru / NIP</label>
                    <input type="text" name="q" value="{{ $q }}" placeholder="Nama, NIP, atau Mapel..."
                        class="w-full text-xs px-3 py-2 rounded-xl border border-garis focus:border-biru focus:ring-1 focus:ring-biru">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-[11px] font-bold text-redup uppercase tracking-wider mb-1">Status Kehadiran</label>
                    <select name="status" class="w-full text-xs px-3 py-2 rounded-xl border border-garis focus:border-biru focus:ring-1 focus:ring-biru">
                        <option value="">Semua Status</option>
                        <option value="masuk" {{ $status === 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="pulang" {{ $status === 'pulang' ? 'selected' : '' }}>Pulang</option>
                        <option value="sakit" {{ $status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="izin" {{ $status === 'izin' ? 'selected' : '' }}>Izin</option>
                    </select>
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label class="block text-[11px] font-bold text-redup uppercase tracking-wider mb-1">Tanggal Mulai</label>
                    <input type="date" name="mulai_tanggal" value="{{ $mulai }}"
                        class="w-full text-xs px-3 py-2 rounded-xl border border-garis focus:border-biru focus:ring-1 focus:ring-biru">
                </div>

                <!-- Tanggal Sampai & Action Buttons -->
                <div>
                    <label class="block text-[11px] font-bold text-redup uppercase tracking-wider mb-1">Tanggal Sampai</label>
                    <div class="flex items-center gap-2">
                        <input type="date" name="sampai_tanggal" value="{{ $sampai }}"
                            class="w-full text-xs px-3 py-2 rounded-xl border border-garis focus:border-biru focus:ring-1 focus:ring-biru">
                        <button type="submit"
                            class="px-4 py-2 bg-biru hover:bg-biru-tua text-emas-terang text-xs font-semibold rounded-xl transition-colors shrink-0">
                            Filter
                        </button>
                        @if ($q || $status || $mulai || $sampai)
                            <a href="{{ route($isAdmin ? 'admin.arsip.show' : 'kepsek.arsip.show', $arsip->id) }}"
                                class="px-2.5 py-2 text-xs text-redup hover:text-merah rounded-xl border border-garis transition-colors shrink-0"
                                title="Reset Filter">
                                ✕
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Log Riwayat Presensi Arsip -->
        <div class="bg-white rounded-2xl border border-garis shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-garis flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-sm sm:text-base text-teks">Data Presensi Diarsipkan</h3>
                    <p class="text-xs text-redup">Menampilkan {{ $presensiList->total() }} catatan riwayat presensi</p>
                </div>
            </div>

            @if ($presensiList->isEmpty())
                <div class="p-12 text-center text-redup text-xs space-y-2">
                    <p class="text-2xl">📋</p>
                    <p class="font-medium text-teks">Tidak ada catatan presensi yang cocok dengan filter pencarian.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-krem text-teks uppercase text-[10px] tracking-wider font-extrabold border-b border-garis">
                            <tr>
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Guru</th>
                                <th class="py-3 px-4">Hari & Tanggal</th>
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Keterangan / Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-garis/60">
                            @foreach ($presensiList as $index => $item)
                                <tr class="hover:bg-krem/30 transition-colors">
                                    <td class="py-3 px-4 text-redup font-medium">
                                        {{ $presensiList->firstItem() + $index }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-teks">{{ $item->nama_guru }}</div>
                                        <div class="text-[11px] text-redup">
                                            {{ $item->mata_pelajaran ? 'Guru ' . $item->mata_pelajaran : ($item->nip_guru ? 'NIP: ' . $item->nip_guru : '-') }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="font-semibold text-teks">{{ $item->tanggal ? $item->tanggal->isoFormat('dddd, D MMM Y') : '-' }}</div>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap font-mono font-medium">
                                        {{ $item->waktu ? substr($item->waktu, 0, 5) . ' WIB' : '-' }}
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        @if ($item->status === 'masuk')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span>✓</span> Masuk
                                            </span>
                                        @elseif ($item->status === 'pulang')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                <span>🚪</span> Pulang
                                            </span>
                                        @elseif ($item->status === 'sakit')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                <span>🏥</span> Sakit
                                            </span>
                                        @elseif ($item->status === 'izin')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span>📝</span> Izin
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="space-y-1">
                                            @if ($item->keterangan)
                                                <div class="text-teks text-xs">{{ $item->keterangan }}</div>
                                            @endif

                                            <div class="flex items-center gap-2">
                                                @if ($item->bukti_sakit)
                                                    <button type="button"
                                                        @click="previewFoto = '{{ $item->bukti_sakit_url }}'; previewJudul = 'Bukti Surat Dokter - {{ addslashes($item->nama_guru) }}'"
                                                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-purple-700 hover:text-purple-900 underline">
                                                        <span>📷</span> Lihat Bukti Sakit
                                                    </button>
                                                @endif

                                                @if ($item->bukti_izin)
                                                    <button type="button"
                                                        @click="previewFoto = '{{ $item->bukti_izin_url }}'; previewJudul = 'Bukti Surat Izin - {{ addslashes($item->nama_guru) }}'"
                                                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 hover:text-amber-900 underline">
                                                        <span>📄</span> Lihat Bukti Izin
                                                    </button>
                                                @endif

                                                @if ($item->approval_status)
                                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md
                                                        {{ $item->approval_status === 'disetujui' ? 'bg-emerald-100 text-emerald-800' : ($item->approval_status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                        Kepsek: {{ ucfirst($item->approval_status) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-garis">
                    {{ $presensiList->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Restore Presensi (Hanya Admin) -->
        @if ($isAdmin)
            <div x-show="modalRestore" x-cloak
                class="fixed inset-0 bg-biru-tua/70 backdrop-blur-xs flex items-center justify-center z-50 p-4"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div @click.away="modalRestore = false"
                    class="bg-white rounded-3xl shadow-2xl border border-garis w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">

                    <div class="p-6 bg-gradient-to-r from-emerald-600 to-teal-700 text-white relative">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-base text-white">Pulihkan Data Presensi</h3>
                                <p class="text-xs text-emerald-100">Kembalikan ke presensi aktif</p>
                            </div>
                        </div>
                        <button type="button" @click="modalRestore = false"
                            class="absolute top-5 right-5 text-white/80 hover:text-white transition-colors text-lg">
                            ✕
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.arsip.restore', $arsip->id) }}" class="p-6 space-y-4">
                        @csrf
                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-900 space-y-2 leading-relaxed">
                            <p class="font-bold flex items-center gap-1.5 text-emerald-950">
                                <span>⚠️</span> Konfirmasi Pemulihan Data
                            </p>
                            <p>
                                Sebanyak <strong>{{ number_format($arsip->total_presensi, 0, ',', '.') }} data presensi</strong> dari arsip "<strong>{{ $arsip->nama_label }}</strong>" akan dimasukkan kembali ke tabel presensi aktif.
                            </p>
                        </div>

                        <label class="flex items-start gap-2.5 p-3 rounded-xl border border-garis bg-krem/40 cursor-pointer text-xs">
                            <input type="checkbox" name="hapus_arsip" value="1" class="mt-0.5 rounded text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <span class="font-bold text-teks">Hapus rekaman arsip ini setelah dipulihkan</span>
                                <p class="text-[11px] text-redup">Centang jika ini adalah pembatalan reset (arsip tidak perlu disimpan lagi).</p>
                            </div>
                        </label>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-garis">
                            <button type="button" @click="modalRestore = false"
                                class="px-4 py-2.5 text-xs font-semibold text-redup hover:bg-krem rounded-xl transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Ya, Pulihkan Sekarang</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Modal Preview Foto Bukti Sakit / Izin -->
        <div x-show="previewFoto" x-cloak
            class="fixed inset-0 bg-biru-tua/80 backdrop-blur-xs flex items-center justify-center z-50 p-4"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div @click.away="previewFoto = null"
                class="bg-white rounded-3xl shadow-2xl border border-garis w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                <div class="p-4 bg-biru text-white flex items-center justify-between">
                    <h4 class="font-bold text-xs" x-text="previewJudul"></h4>
                    <button type="button" @click="previewFoto = null" class="text-white hover:text-emas-terang text-base">✕</button>
                </div>
                <div class="p-4 bg-krem flex items-center justify-center max-h-[70vh] overflow-auto">
                    <img :src="previewFoto" alt="Foto Bukti" class="max-w-full max-h-[60vh] object-contain rounded-xl shadow-xs">
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

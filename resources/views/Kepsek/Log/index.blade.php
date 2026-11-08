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
                        Log Riwayat Presensi Guru
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-200 max-w-xl">
                        Pantau seluruh transaksi check-in dan checkout kehadiran guru beserta waktu dan titik koordinat GPS.
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="bg-white rounded-2xl border border-garis shadow-xs p-5 sm:p-6">
            <div class="flex items-center gap-2.5 mb-4 text-teks font-bold text-sm">
                <div class="w-8 h-8 rounded-xl bg-biru-muda text-biru-tua flex items-center justify-center text-sm font-bold border border-biru/20">
                    🔍
                </div>
                <div>
                    <h3 class="leading-tight text-biru-tua">Pencarian & Filter Riwayat</h3>
                    <p class="text-xs text-redup font-normal">Saring riwayat kehadiran berdasarkan guru, tanggal tertentu, atau status presensi.</p>
                </div>
            </div>

            <form method="GET" class="flex flex-col sm:flex-row sm:items-end gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-teks uppercase tracking-wider mb-1.5">Pilih Guru</label>
                    <select name="guru_id" class="w-full px-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none bg-white">
                        <option value="">Semua Guru</option>
                        @foreach ($daftarGuru as $g)
                            <option value="{{ $g->id }}" {{ (string) $guruId === (string) $g->id ? 'selected' : '' }}>
                                {{ $g->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-teks uppercase tracking-wider mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none bg-white">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-teks uppercase tracking-wider mb-1.5">Status Presensi</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none bg-white">
                        <option value="">Semua Status</option>
                        @foreach (['Hadir' => 'Hadir', 'Sakit' => 'Sakit', 'Izin' => 'Izin', 'masuk' => 'Sudah Masuk', 'pulang' => 'Sudah Pulang'] as $val => $label)
                            <option value="{{ $val }}" {{ strtolower((string)$status) === strtolower((string)$val) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 items-center">
                    <button type="submit" class="px-5 py-2.5 bg-biru text-emas-terang font-semibold text-sm rounded-xl hover:bg-biru-tua border border-emas/40 shadow-xs transition-all">
                        Filter
                    </button>
                    <a href="{{ route('kepsek.log.index') }}" class="px-4 py-2.5 text-sm text-redup hover:text-teks hover:bg-krem rounded-xl transition-colors">Reset</a>
                </div>
            </form>
        </div>

        <!-- Tabel Log -->
        <div class="bg-white rounded-2xl border border-garis shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-biru-muda border-b border-garis text-biru-tua text-xs font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Guru</th>
                            <th class="px-6 py-4 text-center">Jam Masuk</th>
                            <th class="px-6 py-4 text-center">Jam Pulang</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-garis">
                        @forelse ($log as $l)
                            <tr class="hover:bg-biru-muda/30 transition-colors">
                                <td class="px-6 py-4 text-xs font-mono text-redup">
                                    {{ $l->tanggal->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-teks">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-biru-tua text-emas-terang font-bold text-xs flex items-center justify-center shrink-0 border border-emas/30 shadow-xs">
                                            {{ strtoupper(substr($l->nama, 0, 2)) }}
                                        </div>
                                        <span>{{ $l->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs">
                                    @if ($l->jam_masuk)
                                        <span class="inline-flex items-center gap-1 font-bold text-hijau bg-hijau-muda px-2 py-0.5 rounded-md border border-hijau/20">
                                            <span>{{ $l->jam_masuk }}</span>
                                            @if ($l->lat_masuk && $l->long_masuk)
                                                <a href="https://maps.google.com/?q={{ $l->lat_masuk }},{{ $l->long_masuk }}" target="_blank"
                                                   title="GPS Masuk: {{ number_format((float)$l->lat_masuk, 5) }}, {{ number_format((float)$l->long_masuk, 5) }}"
                                                   class="hover:opacity-75 transition-opacity" onclick="event.stopPropagation()">
                                                    📍
                                                </a>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-redup font-semibold">-- : --</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs">
                                    @if ($l->jam_pulang)
                                        <span class="inline-flex items-center gap-1 font-bold text-[#ea580c] bg-orange-50 px-2 py-0.5 rounded-md border border-orange-200">
                                            <span>{{ $l->jam_pulang }}</span>
                                            @if ($l->lat_pulang && $l->long_pulang)
                                                <a href="https://maps.google.com/?q={{ $l->lat_pulang }},{{ $l->long_pulang }}" target="_blank"
                                                   title="GPS Pulang: {{ number_format((float)$l->lat_pulang, 5) }}, {{ number_format((float)$l->long_pulang, 5) }}"
                                                   class="hover:opacity-75 transition-opacity" onclick="event.stopPropagation()">
                                                    📍
                                                </a>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-redup font-semibold">-- : --</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $badgeStyles = [
                                            'Hadir' => 'bg-hijau-muda text-hijau border-hijau/30',
                                            'Izin' => 'bg-biru-muda text-biru-tua border-biru/30',
                                            'Sakit' => 'bg-emas-muda text-emas-tua border-emas/30',
                                        ];
                                    @endphp
                                    @if ($l->status === 'Izin')
                                        <div class="inline-flex flex-col items-center gap-1">
                                            @if ($l->approval_status === 'disetujui')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border bg-hijau-muda text-hijau border-hijau/30">
                                                    ✓ Izin (Disetujui Kepala Sekolah)
                                                </span>
                                            @elseif ($l->approval_status === 'ditolak')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border bg-merah-muda text-merah border-merah/30">
                                                    ✕ Izin (Ditolak Kepala Sekolah)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border bg-emas-muda text-emas-tua border-emas/30">
                                                    ⏳ Izin (Menunggu Persetujuan)
                                                </span>
                                                @if ($l->token_izin)
                                                    <a href="{{ route('izin.approval', ['token' => $l->token_izin]) }}" target="_blank"
                                                       class="text-[10px] text-biru hover:underline font-semibold">
                                                        Tinjau / Setujui →
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeStyles[$l->status] ?? 'bg-krem text-teks border-garis' }}">
                                            {{ $l->status }}
                                        </span>
                                    @endif

                                    @if ($l->keterangan)
                                        <p class="text-[10px] text-redup mt-0.5 italic max-w-xs mx-auto truncate" title="{{ $l->keterangan }}">({{ $l->keterangan }})</p>
                                    @endif
                                    @if (!empty($l->bukti_sakit))
                                        <div class="mt-1">
                                            <a href="{{ asset('storage/' . $l->bukti_sakit) }}" target="_blank"
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-merah-muda text-merah border border-merah/20 hover:bg-merah/15 font-semibold text-[10px] transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>Bukti Sakit</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if (!empty($l->bukti_izin))
                                        <div class="mt-1">
                                            <a href="{{ asset('storage/' . $l->bukti_izin) }}" target="_blank"
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
                                <td colspan="5" class="px-6 py-10 text-center text-redup text-xs">Tidak ada riwayat presensi yang sesuai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($log->hasPages())
                <div class="p-4 border-t border-garis bg-krem/50">
                    {{ $log->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
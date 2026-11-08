<x-app-layout>
    <div x-data="dashboardGuru()" x-init="init()" class="space-y-6">

        @if (session('status'))
            <div class="bg-hijau-muda border border-hijau text-hijau text-sm rounded-xl p-4 flex items-center gap-2">
                <span class="text-base">✅</span>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-merah-muda border border-merah text-merah text-sm rounded-xl p-4 flex items-center gap-2">
                <span class="text-base">⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (! $guru)
            <div class="bg-merah-muda border border-merah text-merah text-sm rounded-xl p-4">
                Data guru untuk akun ini belum terhubung. Hubungi Admin sekolah.
            </div>
        @endif

        <!-- Hero Card (Matching Login Header Aesthetic) -->
        <div class="bg-gradient-to-br from-biru-tua via-biru to-biru-tua rounded-2xl shadow-md p-6 text-white border-b-4 border-emas relative overflow-hidden">
            <!-- Subtle Watermark Logo -->
            <div class="absolute -right-6 -bottom-6 w-44 h-44 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo.png') }}" alt="Watermark Logo" class="w-full h-full object-contain">
            </div>

            <div class="relative z-10 flex justify-between items-start">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-full bg-white border-2 border-emas p-0.5 shrink-0 shadow-sm flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SMK" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <p class="text-[#cdd5ea] text-xs font-medium">Selamat datang, dewan guru</p>
                        <p class="text-xl font-extrabold text-white leading-tight">{{ Auth::user()->nama }}</p>
                        <p class="text-emas-terang text-xs font-semibold mt-0.5 flex items-center gap-1.5 flex-wrap">
                            @if ($guru?->mata_pelajaran)
                                <span class="bg-white/20 text-white px-2 py-0.5 rounded text-[11px] font-bold border border-white/25">
                                    {{ $guru->label_mapel }}
                                </span>
                            @endif
                            <span>SMK Islam Al-Amanah Salem</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Jam Digital & Status Koordinat -->
            <div class="bg-white rounded-xl mt-4 p-4 flex items-center justify-between text-teks border border-garis shadow-xs">
                <div>
                    <p class="text-xs text-redup font-medium">Waktu Saat Ini (WIB)</p>
                    <p class="text-2xl font-bold font-mono text-biru-tua" x-text="jamBerjalan"></p>
                    <p class="text-xs text-redup" x-text="tanggalHariIni"></p>
                </div>
                <span class="text-xs px-3 py-1 rounded-full whitespace-nowrap font-semibold shadow-xs"
                    :class="{
                        'bg-biru-muda text-biru border border-biru/30': statusKoordinat === 'aktif',
                        'bg-merah-muda text-merah border border-merah/30': statusKoordinat === 'ditolak',
                        'bg-emas-muda text-emas-tua border border-emas/30': statusKoordinat === 'mencari'
                    }"
                    x-text="statusKoordinat === 'aktif' ? 'GPS Terkoneksi' : (statusKoordinat === 'ditolak' ? 'GPS Nonaktif' : 'Mencari Lokasi...')">
                </span>
            </div>

            <!-- Informasi Jam Operasional Sekolah -->
            <div class="bg-biru-tua/90 border border-emas/30 rounded-xl mt-3 p-3 text-xs text-[#cdd5ea] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div class="flex items-center gap-1.5">
                    <span class="text-sm">⏰</span>
                    <span><strong>Jam Masuk:</strong> {{ \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_mulai ?? '06:00:00')->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_selesai ?? '12:00:00')->format('H:i') }} WIB (Toleransi: <span class="text-emas-terang font-semibold">{{ \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_toleransi)->format('H:i') }}</span>)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-sm">🚪</span>
                    <span><strong>Jam Pulang:</strong> {{ \Illuminate\Support\Carbon::parse($pengaturan->jam_pulang_minimal)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($pengaturan->jam_pulang_selesai ?? '19:00:00')->format('H:i') }} WIB</span>
                </div>
            </div>

            <!-- Jam Masuk & Pulang Hari Ini -->
            <div class="bg-white rounded-xl mt-3 p-4 grid grid-cols-2 gap-4 text-teks text-center border border-garis shadow-xs">
                <div>
                    <p class="text-xs text-redup font-medium">Catatan Masuk Anda</p>
                    <p class="text-lg font-bold font-mono text-biru">
                        {{ $jamMasuk ? \Illuminate\Support\Carbon::parse($jamMasuk)->format('H:i') . ' WIB' : '--:--' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-redup font-medium">Catatan Pulang Anda</p>
                    <p class="text-lg font-bold font-mono text-emas-tua">
                        {{ $jamPulang ? \Illuminate\Support\Carbon::parse($jamPulang)->format('H:i') . ' WIB' : '--:--' }}
                    </p>
                </div>
            </div>

            <!-- Card Validasi Geofencing & Jarak Real-Time -->
            <div class="bg-white rounded-xl mt-3 p-4 text-teks border border-garis shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📍</span>
                        <div>
                            <p class="text-xs font-bold text-teks">
                                Geofence Presensi (Maksimal {{ $pengaturan->radius }} Meter)
                            </p>
                            <p class="text-[11px] text-redup">
                                Titik Sekolah: {{ $pengaturan->latitude_sekolah }}, {{ $pengaturan->longitude_sekolah }}
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="cekLokasi()"
                            class="px-2.5 py-1 text-xs font-medium rounded-md bg-krem hover:bg-garis text-teks border border-garis transition flex items-center gap-1.5"
                            title="Perbarui titik GPS Anda">
                        <svg class="w-3.5 h-3.5 text-biru" :class="{'animate-spin': statusKoordinat === 'mencari'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Cek Lokasi</span>
                    </button>
                </div>

                <!-- Status 1: Mencari Lokasi -->
                <div x-show="statusKoordinat === 'mencari'" class="flex items-center gap-2 text-xs text-emas-tua bg-emas-muda p-2.5 rounded-md border border-[#ecd9a0]">
                    <svg class="animate-spin w-4 h-4 text-emas shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span>Mendeteksi koordinat GPS perangkat Anda...</span>
                </div>

                <!-- Status 2: GPS Ditolak / Tidak Aktif -->
                <div x-show="statusKoordinat === 'ditolak'" x-cloak class="flex items-center gap-2 text-xs text-merah bg-merah-muda p-2.5 rounded-md border border-[#f8b4b0]">
                    <span class="text-base shrink-0">⚠️</span>
                    <span>Izin lokasi (GPS) tidak aktif. Mohon izinkan akses lokasi pada browser untuk dapat melakukan presensi.</span>
                </div>

                <!-- Status 3: Jarak Terdeteksi -->
                <div x-show="statusKoordinat === 'aktif' && jarakSekarang !== null" x-cloak>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-2.5 rounded-md text-xs font-medium gap-2"
                         :class="statusJarak === 'dalam' ? 'bg-hijau-muda text-hijau border border-hijau/30' : 'bg-merah-muda text-merah border border-merah/30'">
                        <div class="flex items-center gap-2">
                            <span class="text-base" x-text="statusJarak === 'dalam' ? '✅' : '🚫'"></span>
                            <div>
                                <span class="font-bold text-sm" x-text="'Jarak Anda: ' + jarakSekarang + ' meter'"></span>
                                <span class="text-[11px] block sm:inline sm:ml-1"
                                      :class="statusJarak === 'dalam' ? 'text-hijau' : 'text-merah'"
                                      x-text="statusJarak === 'dalam' ? '(Memenuhi syarat: dalam batas maksimal ' + maxRadius + 'm)' : '(Di luar jangkauan: batas maksimal ' + maxRadius + 'm)'"></span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold self-start sm:self-auto shrink-0"
                              :class="statusJarak === 'dalam' ? 'bg-hijau text-white' : 'bg-merah text-white'"
                              x-text="statusJarak === 'dalam' ? 'Bisa Presensi' : 'Di Luar Radius'">
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-redum mt-1.5 px-0.5">
                        <span x-show="akurasiGps">Akurasi GPS: ±<strong x-text="akurasiGps"></strong> meter</span>
                        <span x-show="statusJarak === 'luar'" class="text-merah font-medium">Dekati titik sekolah untuk presensi</span>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="font-bold text-teks text-base flex items-center gap-2">
            <span>Pilih Aktivitas:</span>
        </h3>
        <div class="grid grid-cols-2 gap-4">
            <button @click="submitPresensi('{{ route('guru.presensi.masuk') }}', 'masuk')"
                class="bg-hijau hover:bg-[#25633e] text-white rounded-xl p-5 flex flex-col items-center gap-1.5 disabled:opacity-50 transition relative overflow-hidden shadow-xs cursor-pointer"
                :disabled="loading">
                <span class="text-2xl">➡️</span>
                <span class="font-bold text-sm">Absen Masuk</span>
                <div class="flex flex-wrap gap-1 justify-center">
                    <span class="text-[10px] text-white bg-black/15 px-2 py-0.5 rounded-full font-medium">Maks. {{ $pengaturan->radius }}m</span>
                    <span class="text-[10px] text-white bg-black/15 px-2 py-0.5 rounded-full">{{ \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_mulai ?? '06:00:00')->format('H:i') }}-{{ \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_selesai ?? '12:00:00')->format('H:i') }}</span>
                </div>
            </button>

            <button @click="submitPresensi('{{ route('guru.presensi.pulang') }}', 'pulang')"
                class="bg-biru hover:bg-biru-tua text-white rounded-xl p-5 flex flex-col items-center gap-1.5 disabled:opacity-50 transition relative overflow-hidden shadow-xs cursor-pointer"
                :disabled="loading">
                <span class="text-2xl">⬅️</span>
                <span class="font-bold text-sm">Absen Pulang</span>
                <div class="flex flex-wrap gap-1 justify-center">
                    <span class="text-[10px] text-white bg-black/15 px-2 py-0.5 rounded-full">Maks. {{ $pengaturan->radius }}m</span>
                    <span class="text-[10px] text-white bg-black/15 px-2 py-0.5 rounded-full">Min. {{ \Illuminate\Support\Carbon::parse($pengaturan->jam_pulang_minimal)->format('H:i') }}</span>
                </div>
            </button>

            <button @click="modalStatus = 'sakit'"
                class="bg-merah hover:bg-[#992f26] text-white rounded-xl p-5 flex flex-col items-center gap-1.5 transition shadow-xs cursor-pointer">
                <span class="text-2xl">🌡️</span>
                <span class="font-bold text-sm">Sakit</span>
                <span class="text-[10px] text-white bg-black/15 px-2 py-0.5 rounded-full">Keterangan</span>
            </button>

            <button @click="modalStatus = 'izin'"
                class="bg-emas hover:bg-emas-tua text-white rounded-xl p-5 flex flex-col items-center gap-1.5 transition shadow-xs cursor-pointer">
                <span class="text-2xl">📅</span>
                <span class="font-bold text-sm">Izin</span>
                <span class="text-[10px] text-white bg-black/15 px-2 py-0.5 rounded-full">Keterangan</span>
            </button>
        </div>

        <!-- Tabel Riwayat Absen -->
        <div class="bg-white rounded-xl border border-garis shadow-xs overflow-hidden">
            <div class="p-5 border-b border-garis bg-krem">
                <h3 class="font-bold text-teks text-sm">Riwayat Absen Pribadi Anda (5 Hari Terakhir)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-biru-muda text-biru-tua font-bold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Waktu</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Catatan Koordinat</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#efe8d8] bg-white">
                        @forelse ($riwayat as $r)
                            <tr class="hover:bg-[#faf7f0] transition-colors">
                                <td class="px-5 py-3.5 text-xs text-teks font-medium">{{ $r->tanggal->format('d/m/Y') }}</td>
                                <td class="px-5 py-3.5 text-xs font-mono font-semibold text-biru">{{ \Illuminate\Support\Carbon::parse($r->waktu)->format('H:i') }} WIB</td>
                                <td class="px-5 py-3.5">
                                    <div class="space-y-1">
                                        <span @class([
                                            'px-2.5 py-1 rounded-full text-xs font-semibold inline-block',
                                            'bg-hijau-muda text-hijau' => $r->status === 'masuk',
                                            'bg-biru-muda text-biru' => $r->status === 'pulang',
                                            'bg-merah-muda text-merah' => $r->status === 'sakit',
                                            'bg-emas-muda text-emas-tua' => $r->status === 'izin' && (! $r->approval_status || $r->approval_status === 'menunggu'),
                                            'bg-hijau-muda text-hijau' => $r->status === 'izin' && $r->approval_status === 'disetujui',
                                            'bg-merah-muda text-merah' => $r->status === 'izin' && $r->approval_status === 'ditolak',
                                        ])>
                                            @if ($r->status === 'izin')
                                                @if ($r->approval_status === 'disetujui')
                                                    ✓ Izin (Disetujui Kepala Sekolah)
                                                @elseif ($r->approval_status === 'ditolak')
                                                    ✕ Izin (Ditolak Kepala Sekolah)
                                                @else
                                                    ⏳ Izin (Menunggu Persetujuan Kepsek)
                                                @endif
                                            @else
                                                {{ ucfirst($r->status) }}
                                            @endif
                                        </span>

                                        @if ($r->status === 'izin' && $r->approval_status === 'menunggu' && $r->wa_link)
                                            <div>
                                                <a href="{{ $r->wa_link }}" target="_blank"
                                                   class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#25D366] text-white hover:bg-[#1faa53] shadow-2xs transition">
                                                    <span>💬 Kirim WA</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-redup">
                                    @if ($r->latitude && $r->longitude)
                                        <span class="bg-krem border border-garis px-2 py-0.5 rounded text-[11px] font-mono">📍 {{ number_format((float)$r->latitude, 4) }}, {{ number_format((float)$r->longitude, 4) }}</span>
                                    @elseif ($r->status === 'sakit' && $r->bukti_sakit)
                                        <div class="flex items-center gap-2">
                                            <span class="text-teks font-medium">{{ $r->keterangan }}</span>
                                            <a href="{{ asset('storage/' . $r->bukti_sakit) }}" target="_blank"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-merah-muda text-merah border border-merah/30 hover:bg-merah/20 font-semibold text-[11px] transition-colors shadow-2xs">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>Lihat Bukti</span>
                                            </a>
                                        </div>
                                    @elseif ($r->status === 'izin')
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-teks font-medium">{{ $r->keterangan }}</span>
                                                @if ($r->bukti_izin)
                                                    <a href="{{ asset('storage/' . $r->bukti_izin) }}" target="_blank"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emas-muda text-emas-tua border border-emas/30 hover:bg-emas/20 font-semibold text-[11px] transition-colors shadow-2xs">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        <span>Lihat Bukti</span>
                                                    </a>
                                                @endif
                                            </div>
                                            @if ($r->catatan_kepsek)
                                                <p class="text-[11px] text-redup italic">
                                                    <strong>Catatan Kepsek:</strong> {{ $r->catatan_kepsek }}
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        {{ $r->keterangan ?? '-' }}
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Edit -->
                                        <button type="button"
                                            @click="bukaModalEdit({{ json_encode([
                                                'id' => $r->id,
                                                'status' => ucfirst($r->status),
                                                'statusCode' => $r->status,
                                                'tanggal' => $r->tanggal->format('d/m/Y'),
                                                'waktu' => \Illuminate\Support\Carbon::parse($r->waktu)->format('H:i'),
                                                'keterangan' => $r->keterangan ?? '',
                                                'file_url' => $r->bukti_sakit ? asset('storage/' . $r->bukti_sakit) : ($r->bukti_izin ? asset('storage/' . $r->bukti_izin) : null),
                                                'update_url' => route('guru.presensi.update', $r),
                                            ]) }})"
                                            title="Edit Presensi"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-biru hover:bg-biru-muda text-xs font-semibold transition-colors border border-biru/20 shadow-2xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span>Edit</span>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form method="POST" action="{{ route('guru.presensi.destroy', $r) }}"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data presensi {{ ucfirst($r->status) }} tanggal {{ $r->tanggal->format('d/m/Y') }} ini?');"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                title="Hapus Presensi"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-merah hover:bg-merah-muda text-xs font-semibold transition-colors border border-merah/20 shadow-2xs cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-redup text-xs">Belum ada riwayat presensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Izin / Sakit -->
        <div x-show="modalStatus" x-cloak class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl border border-garis shadow-2xl w-full max-w-md overflow-hidden" @click.outside="modalStatus = null">
                <div class="bg-biru text-white p-4 border-b-2 border-emas flex items-center justify-between">
                    <h3 class="font-bold text-sm text-white" x-text="modalStatus === 'sakit' ? 'Formulir Keterangan Sakit' : 'Formulir Pengajuan Izin'"></h3>
                    <button type="button" @click="modalStatus = null" class="text-[#cdd5ea] hover:text-white">✕</button>
                </div>
                <form method="POST" :action="modalStatus === 'sakit' ? '{{ route('guru.presensi.sakit') }}' : '{{ route('guru.presensi.izin') }}'"
                      enctype="multipart/form-data" class="p-5 space-y-4"
                      x-data="{ filePreview: null, fileName: '' }">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-teks uppercase tracking-wider mb-1.5">
                            Keterangan Alasan <span class="text-merah">*</span>
                        </label>
                        <textarea name="keterangan" rows="3" required
                            class="w-full rounded-xl border border-garis text-sm p-3 focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none bg-[#fdfcf9] transition-all"
                            :placeholder="modalStatus === 'sakit' ? 'Contoh: Sakit demam tinggi, flu berat, perlu istirahat dokter...' : 'Contoh: Ada keperluan dinas MGMP / keluarga...'"></textarea>
                    </div>

                    <!-- Upload Bukti Sakit / Izin -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-teks uppercase tracking-wider">
                                <span x-text="modalStatus === 'sakit' ? 'Foto Bukti / Surat Dokter' : 'Surat / Foto Bukti Izin (Opsional)'"></span>
                                <span x-show="modalStatus === 'sakit'" class="text-merah">*</span>
                            </label>
                            <span class="text-[11px] font-mono text-redup" x-text="modalStatus === 'sakit' ? 'JPG, JPEG, PNG' : 'JPG, PNG, PDF'"></span>
                        </div>

                        <div class="border-2 border-dashed border-garis hover:border-biru rounded-xl p-4 text-center bg-[#fdfcf9] transition-all relative">
                            <template x-if="!filePreview">
                                <div class="space-y-2 py-2">
                                    <div class="w-10 h-10 rounded-xl bg-biru-muda text-biru mx-auto flex items-center justify-center text-xl">
                                        📄
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-biru-tua"
                                           x-text="modalStatus === 'sakit' ? 'Klik atau tempel foto surat sakit di sini' : 'Klik atau unggah foto/surat izin dinas/pribadi (opsional)'"></p>
                                        <p class="text-[11px] text-redup mt-0.5">Maksimal ukuran file: <strong>5 MB</strong></p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="filePreview">
                                <div class="relative inline-block py-1">
                                    <template x-if="filePreview !== 'pdf'">
                                        <img :src="filePreview" alt="Preview Bukti" class="max-h-48 rounded-xl border border-garis shadow-xs mx-auto object-contain">
                                    </template>
                                    <template x-if="filePreview === 'pdf'">
                                        <div class="p-4 bg-biru-muda rounded-xl border border-biru/20 text-biru-tua font-bold text-xs">
                                            📄 Dokumen PDF Terlampir
                                        </div>
                                    </template>
                                    <p class="text-xs font-medium text-teks mt-2 truncate max-w-xs mx-auto" x-text="fileName"></p>
                                </div>
                            </template>

                            <input type="file"
                                :name="modalStatus === 'sakit' ? 'bukti_sakit' : 'bukti_izin'"
                                x-ref="fileInput"
                                :accept="modalStatus === 'sakit' ? '.jpg,.jpeg,.png,image/jpeg,image/png' : '.jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf'"
                                :required="modalStatus === 'sakit'"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        fileName = file.name;
                                        if (file.type === 'application/pdf') {
                                            filePreview = 'pdf';
                                        } else {
                                            const reader = new FileReader();
                                            reader.onload = (e) => { filePreview = e.target.result; };
                                            reader.readAsDataURL(file);
                                        }
                                    }
                                "
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                :class="{ 'pointer-events-none': filePreview }">
                        </div>

                        <template x-if="filePreview">
                            <div class="flex justify-between items-center pt-1 text-xs">
                                <button type="button" @click="$refs.fileInput.click()" class="text-biru hover:underline font-semibold">
                                    Ganti Berkas
                                </button>
                                <button type="button" @click="filePreview = null; fileName = ''; $refs.fileInput.value = ''" class="text-merah hover:underline font-semibold">
                                    Hapus Berkas
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-garis">
                        <p x-show="modalStatus === 'izin'" class="text-[11px] text-redup text-left">
                            📲 Anda akan langsung diarahkan ke chat WhatsApp Kepala Sekolah dengan draf pesan otomatis.
                        </p>
                        <div class="flex items-center justify-end gap-2 w-full sm:w-auto shrink-0">
                            <button type="button" @click="modalStatus = null; filePreview = null; fileName = ''"
                                class="px-4 py-2.5 text-xs font-semibold text-redup hover:bg-krem rounded-xl transition-colors cursor-pointer">
                                Batal
                            </button>
                            <button type="submit"
                                :class="modalStatus === 'izin' ? 'bg-[#25D366] hover:bg-[#1faa53] text-white shadow-md' : 'bg-biru hover:bg-biru-tua text-emas-terang'"
                                class="px-5 py-2.5 text-xs font-bold rounded-xl border border-black/10 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <template x-if="modalStatus === 'izin'">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        <span>Kirim Izin ke WhatsApp</span>
                                    </span>
                                </template>
                                <template x-if="modalStatus !== 'izin'">
                                    <span>Kirim Keterangan</span>
                                </template>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Presensi -->
        <div x-show="modalEdit" x-cloak class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl border border-garis shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200" @click.outside="tutupModalEdit()">
                <div class="bg-biru text-white p-4 border-b-2 border-emas flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-base">✏️</span>
                        <h3 class="font-bold text-sm text-white">
                            Edit Presensi: <span class="text-emas-terang" x-text="modalEdit?.status"></span>
                        </h3>
                    </div>
                    <button type="button" @click="tutupModalEdit()" class="text-[#cdd5ea] hover:text-white">✕</button>
                </div>

                <form method="POST" :action="modalEdit?.update_url"
                      enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Info Tanggal & Waktu -->
                    <div class="grid grid-cols-2 gap-3 p-3 bg-krem rounded-xl border border-garis text-xs">
                        <div>
                            <span class="text-redup block text-[10px] font-semibold uppercase tracking-wider">Tanggal</span>
                            <span class="font-bold text-teks" x-text="modalEdit?.tanggal"></span>
                        </div>
                        <div>
                            <span class="text-redup block text-[10px] font-semibold uppercase tracking-wider">Waktu</span>
                            <span class="font-mono font-bold text-biru" x-text="modalEdit?.waktu + ' WIB'"></span>
                        </div>
                    </div>

                    <!-- Keterangan / Alasan -->
                    <div>
                        <label class="block text-xs font-bold text-teks uppercase tracking-wider mb-1.5">
                            Keterangan / Alasan
                        </label>
                        <textarea name="keterangan" rows="3" x-model="modalEdit.keterangan"
                            class="w-full rounded-xl border border-garis text-sm p-3 focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none bg-[#fdfcf9] transition-all"
                            placeholder="Tuliskan keterangan / alasan..."></textarea>
                    </div>

                    <!-- Ganti Berkas / Foto Bukti (Jika Sakit atau Izin) -->
                    <template x-if="modalEdit && (modalEdit.statusCode === 'sakit' || modalEdit.statusCode === 'izin')">
                        <div class="space-y-2 pt-1 border-t border-garis">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-teks uppercase tracking-wider">
                                    Unggah / Ganti Bukti <span class="text-redup font-normal lowercase">(opsional)</span>
                                </label>
                                <span class="text-[11px] font-mono text-redup">JPG, PNG, PDF</span>
                            </div>

                            <!-- Preview file saat ini jika ada -->
                            <template x-if="modalEdit.file_url">
                                <div class="p-2.5 rounded-xl bg-biru-muda border border-biru/20 flex items-center justify-between text-xs">
                                    <span class="text-biru-tua font-medium">📄 Berkas lampiran saat ini sudah ada</span>
                                    <a :href="modalEdit.file_url" target="_blank" class="text-biru hover:underline font-bold text-[11px]">
                                        Lihat Berkas →
                                    </a>
                                </div>
                            </template>

                            <div class="border-2 border-dashed border-garis hover:border-biru rounded-xl p-3 text-center bg-[#fdfcf9] transition-all relative">
                                <template x-if="!editFilePreview">
                                    <div class="py-1">
                                        <p class="text-xs font-semibold text-biru-tua">Pilih berkas baru jika ingin mengganti</p>
                                        <p class="text-[10px] text-redup mt-0.5">Maksimal file: <strong>5 MB</strong></p>
                                    </div>
                                </template>

                                <template x-if="editFilePreview">
                                    <div class="relative inline-block py-1">
                                        <template x-if="editFilePreview !== 'pdf'">
                                            <img :src="editFilePreview" alt="Preview Baru" class="max-h-32 rounded-lg border border-garis shadow-xs mx-auto object-contain">
                                        </template>
                                        <template x-if="editFilePreview === 'pdf'">
                                            <div class="p-3 bg-biru-muda rounded-lg border border-biru/20 text-biru-tua font-bold text-xs">
                                                📄 Berkas PDF Baru Terpilih
                                            </div>
                                        </template>
                                        <p class="text-xs font-medium text-teks mt-1 truncate max-w-xs mx-auto" x-text="editFileName"></p>
                                    </div>
                                </template>

                                <input type="file" name="bukti_file" x-ref="editFileInput"
                                    accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                                    @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            editFileName = file.name;
                                            if (file.type === 'application/pdf') {
                                                editFilePreview = 'pdf';
                                            } else {
                                                const reader = new FileReader();
                                                reader.onload = (e) => { editFilePreview = e.target.result; };
                                                reader.readAsDataURL(file);
                                            }
                                        }
                                    "
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    :class="{ 'pointer-events-none': editFilePreview }">
                            </div>

                            <template x-if="editFilePreview">
                                <div class="flex justify-end pt-0.5 text-xs">
                                    <button type="button" @click="editFilePreview = null; editFileName = ''; $refs.editFileInput.value = ''"
                                            class="text-merah hover:underline font-semibold text-[11px]">
                                        Batalkan Ganti Berkas
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>

                    <div class="flex justify-end gap-2 pt-3 border-t border-garis">
                        <button type="button" @click="tutupModalEdit()"
                            class="px-4 py-2 text-xs font-semibold text-redup hover:bg-krem rounded-xl transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-biru text-emas-terang text-xs font-bold rounded-xl hover:bg-biru-tua border border-emas/40 shadow-xs transition-all cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function dashboardGuru() {
            return {
                tanggalHariIni: '',
                jamBerjalan: '',
                statusKoordinat: 'mencari',
                modalStatus: null,
                modalEdit: null,
                editFilePreview: null,
                editFileName: '',

                bukaModalEdit(data) {
                    this.modalEdit = data;
                    this.editFilePreview = null;
                    this.editFileName = '';
                },

                tutupModalEdit() {
                    this.modalEdit = null;
                    this.editFilePreview = null;
                    this.editFileName = '';
                },

                loading: false,
                targetLat: {{ (float) $pengaturan->latitude_sekolah }},
                targetLng: {{ (float) $pengaturan->longitude_sekolah }},
                maxRadius: {{ (int) $pengaturan->radius }},
                jamMasukMulai: '{{ \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_mulai ?? "06:00:00")->format("H:i") }}',
                jamMasukSelesai: '{{ \Illuminate\Support\Carbon::parse($pengaturan->jam_masuk_selesai ?? "12:00:00")->format("H:i") }}',
                jamPulangMinimal: '{{ \Illuminate\Support\Carbon::parse($pengaturan->jam_pulang_minimal)->format("H:i") }}',
                jamPulangSelesai: '{{ \Illuminate\Support\Carbon::parse($pengaturan->jam_pulang_selesai ?? "19:00:00")->format("H:i") }}',
                userLat: null,
                userLng: null,
                jarakSekarang: null,
                statusJarak: 'memeriksa',
                akurasiGps: null,

                init() {
                    this.updateWaktu();
                    setInterval(() => this.updateWaktu(), 1000);
                    this.cekLokasi();
                },

                updateWaktu() {
                    const now = new Date();
                    this.tanggalHariIni = now.toLocaleDateString('id-ID', {
                        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                    });
                    this.jamBerjalan = now.toLocaleTimeString('id-ID', { hour12: false }) + ' WIB';
                },

                hitungJarak(lat1, lon1, lat2, lon2) {
                    const R = 6371000;
                    const dLat = (lat2 - lat1) * Math.PI / 180;
                    const dLon = (lon2 - lon1) * Math.PI / 180;
                    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                              Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    return Math.round(R * c);
                },

                cekLokasi() {
                    if (! navigator.geolocation) {
                        this.statusKoordinat = 'ditolak';
                        this.statusJarak = 'gagal';
                        return;
                    }

                    this.statusKoordinat = 'mencari';

                    navigator.geolocation.getCurrentPosition(
                        (posisi) => {
                            this.userLat = posisi.coords.latitude;
                            this.userLng = posisi.coords.longitude;
                            this.akurasiGps = Math.round(posisi.coords.accuracy || 0);
                            this.statusKoordinat = 'aktif';

                            const d = this.hitungJarak(this.userLat, this.userLng, this.targetLat, this.targetLng);
                            this.jarakSekarang = d;
                            this.statusJarak = d <= this.maxRadius ? 'dalam' : 'luar';
                        },
                        (err) => {
                            this.statusKoordinat = 'ditolak';
                            this.statusJarak = 'gagal';
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                },

                submitPresensi(url, jenis) {
                    const now = new Date();
                    const nowHH = String(now.getHours()).padStart(2, '0');
                    const nowMM = String(now.getMinutes()).padStart(2, '0');
                    const nowHM = `${nowHH}:${nowMM}`;

                    if (jenis === 'masuk') {
                        if (nowHM < this.jamMasukMulai) {
                            alert(`Presensi Masuk Belum Dibuka!\n\nJam saat ini: ${nowHM} WIB\nPresensi masuk dibuka mulai pukul ${this.jamMasukMulai} WIB.`);
                            return;
                        }
                        if (nowHM > this.jamMasukSelesai) {
                            alert(`Presensi Masuk Telah Ditutup!\n\nJam saat ini: ${nowHM} WIB\nBatas akhir presensi masuk untuk hari ini adalah pukul ${this.jamMasukSelesai} WIB.`);
                            return;
                        }
                    } else if (jenis === 'pulang') {
                        if (nowHM < this.jamPulangMinimal) {
                            alert(`Presensi Pulang Belum Dibuka!\n\nJam saat ini: ${nowHM} WIB\nJam pulang minimal adalah pukul ${this.jamPulangMinimal} WIB.`);
                            return;
                        }
                        if (nowHM > this.jamPulangSelesai) {
                            alert(`Presensi Pulang Telah Ditutup!\n\nJam saat ini: ${nowHM} WIB\nBatas akhir presensi pulang adalah pukul ${this.jamPulangSelesai} WIB.`);
                            return;
                        }
                    }

                    if (! navigator.geolocation) {
                        alert('Browser tidak mendukung geolokasi GPS.');
                        return;
                    }

                    this.loading = true;

                    navigator.geolocation.getCurrentPosition((posisi) => {
                        const latVal = posisi.coords.latitude;
                        const lngVal = posisi.coords.longitude;
                        const d = this.hitungJarak(latVal, lngVal, this.targetLat, this.targetLng);
                        this.jarakSekarang = d;
                        this.userLat = latVal;
                        this.userLng = lngVal;

                        if (d > this.maxRadius) {
                            this.loading = false;
                            this.statusJarak = 'luar';
                            alert(`Presensi Ditolak!\n\nJarak Anda saat ini: ${d} meter dari titik lokasi sekolah.\nBatas maksimal radius yang ditentukan adalah ${this.maxRadius} meter.\n\nSilakan berada di dalam area lokasi (radius ${this.maxRadius}m) untuk melakukan presensi.`);
                            return;
                        }

                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                        form.appendChild(csrf);

                        const lat = document.createElement('input');
                        lat.type = 'hidden';
                        lat.name = 'latitude';
                        lat.value = latVal;
                        form.appendChild(lat);

                        const lng = document.createElement('input');
                        lng.type = 'hidden';
                        lng.name = 'longitude';
                        lng.value = lngVal;
                        form.appendChild(lng);

                        document.body.appendChild(form);
                        form.submit();
                    }, (err) => {
                        this.loading = false;
                        this.statusKoordinat = 'ditolak';
                        alert('Gagal mengambil koordinat lokasi. Pastikan izin GPS/lokasi diaktifkan di browser perangkat Anda.');
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    });
                }
            }
        }
    </script>
</x-app-layout>
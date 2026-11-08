<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f4efe4]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Persetujuan Izin Guru - SMK Islam Al-Amanah Salem</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f4efe4] text-[#2b2a27] min-h-screen flex flex-col justify-between py-6 px-4 sm:px-6">

    <div class="max-w-lg mx-auto w-full space-y-5">

        <!-- Header Sekolah -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center p-2.5 rounded-2xl bg-white shadow-xs border border-garis">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMK" class="w-12 h-12 object-contain">
            </div>
            <div>
                <p class="text-[11px] font-bold text-emas-tua uppercase tracking-widest">SMK Islam Al-Amanah Salem</p>
                <h1 class="text-xl sm:text-2xl font-extrabold text-biru-tua leading-tight">Konfirmasi Izin Guru</h1>
                <p class="text-xs text-redup">Persetujuan Kehadiran oleh Kepala Sekolah</p>
            </div>
        </div>

        <!-- Alert Notifikasi Status Proses -->
        @if (session('status_proses'))
            <div class="p-4 rounded-2xl bg-white border border-hijau/40 shadow-xs flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-hijau-muda text-hijau flex items-center justify-center text-lg font-bold shrink-0">
                    ✓
                </div>
                <div class="text-xs sm:text-sm font-semibold text-[#1f4e30]">
                    {{ session('status_proses') }}
                </div>
            </div>
        @endif

        <!-- Card Informasi Guru & Izin -->
        <div class="bg-white rounded-3xl border border-garis shadow-sm overflow-hidden">
            <!-- Header Kartu -->
            <div class="bg-gradient-to-r from-biru-tua to-biru text-white p-5 border-b-2 border-emas flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-emas text-biru-tua font-black text-xl flex items-center justify-center shadow-md border border-emas-terang shrink-0">
                        {{ strtoupper(substr($presensi->guru->nama ?? 'G', 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="font-bold text-base sm:text-lg text-white leading-tight">
                            {{ $presensi->guru->nama ?? 'Guru' }}
                        </h2>
                        <p class="text-xs text-emas-terang">
                            {{ $presensi->guru->mata_pelajaran ? 'Guru ' . $presensi->guru->mata_pelajaran : 'Tenaga Pendidik' }}
                            @if ($presensi->guru?->nip)
                                • <span class="font-mono text-[11px] text-slate-200">NIP: {{ $presensi->guru->nip }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Isi Permohonan -->
            <div class="p-5 sm:p-6 space-y-4 text-xs sm:text-sm">
                <!-- Baris Tanggal & Waktu -->
                <div class="grid grid-cols-2 gap-3 p-3.5 bg-krem rounded-2xl border border-garis">
                    <div>
                        <span class="block text-[11px] font-semibold text-redup uppercase tracking-wider">Tanggal Izin</span>
                        <span class="font-bold text-biru-tua text-xs sm:text-sm">
                            {{ $presensi->tanggal ? $presensi->tanggal->isoFormat('dddd, D MMMM Y') : '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-semibold text-redup uppercase tracking-wider">Jam Pengajuan</span>
                        <span class="font-mono font-bold text-biru text-xs sm:text-sm">
                            {{ \Illuminate\Support\Carbon::parse($presensi->waktu)->format('H:i') }} WIB
                        </span>
                    </div>
                </div>

                <!-- Alasan / Keterangan -->
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-bold text-redup uppercase tracking-wider">
                        Alasan / Keterangan Izin
                    </label>
                    <div class="p-4 rounded-2xl bg-[#faf7f0] border border-garis text-teks font-medium leading-relaxed">
                        {{ $presensi->keterangan ?? 'Tidak ada keterangan.' }}
                    </div>
                </div>

                <!-- Bukti Surat / Dokumen Izin -->
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-bold text-redup uppercase tracking-wider">
                        Bukti Surat / Foto Lampiran
                    </label>
                    @if ($presensi->bukti_izin)
                        @php
                            $ext = strtolower(pathinfo($presensi->bukti_izin, PATHINFO_EXTENSION));
                        @endphp
                        @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                            <div class="border border-garis rounded-2xl p-2 bg-[#fdfcf9] text-center space-y-2">
                                <a href="{{ asset('storage/' . $presensi->bukti_izin) }}" target="_blank" class="block group">
                                    <img src="{{ asset('storage/' . $presensi->bukti_izin) }}" alt="Bukti Izin"
                                         class="max-h-64 rounded-xl mx-auto object-contain border border-garis shadow-2xs group-hover:opacity-95 transition-all">
                                </a>
                                <a href="{{ asset('storage/' . $presensi->bukti_izin) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-biru hover:text-biru-tua underline">
                                    <span>🔍 Buka Foto Ukuran Penuh</span>
                                </a>
                            </div>
                        @else
                            <div class="p-3.5 rounded-2xl bg-biru-muda border border-biru/20 flex items-center justify-between">
                                <span class="font-medium text-biru-tua text-xs">Dokumen Lampiran (PDF)</span>
                                <a href="{{ asset('storage/' . $presensi->bukti_izin) }}" target="_blank"
                                   class="px-3 py-1.5 bg-biru text-white text-xs font-semibold rounded-xl hover:bg-biru-tua transition">
                                    Lihat Dokumen
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="p-3 rounded-2xl bg-krem border border-garis text-redup text-xs italic">
                            Guru tidak melampirkan file surat/dokumen fisik.
                        </div>
                    @endif
                </div>

                <!-- Status Approval Saat Ini -->
                <div class="pt-2 border-t border-garis">
                    @if ($presensi->approval_status === 'disetujui')
                        <div class="p-4 rounded-2xl bg-hijau-muda border border-hijau/30 text-hijau space-y-1.5 text-center">
                            <span class="inline-flex items-center gap-1 text-sm font-extrabold uppercase tracking-wide">
                                <span>✅</span> Disetujui oleh Kepala Sekolah
                            </span>
                            @if ($presensi->approved_at)
                                <p class="text-xs text-[#205534]">
                                    Pada {{ $presensi->approved_at->isoFormat('dddd, D MMMM Y [pukul] HH:mm') }} WIB
                                </p>
                            @endif
                            @if ($presensi->catatan_kepsek)
                                <p class="text-xs font-medium text-teks pt-1 border-t border-hijau/20 mt-1">
                                    <strong>Catatan:</strong> "{{ $presensi->catatan_kepsek }}"
                                </p>
                            @endif
                        </div>
                    @elseif ($presensi->approval_status === 'ditolak')
                        <div class="p-4 rounded-2xl bg-merah-muda border border-merah/30 text-merah space-y-1.5 text-center">
                            <span class="inline-flex items-center gap-1 text-sm font-extrabold uppercase tracking-wide">
                                <span>❌</span> Permohonan Izin Ditolak
                            </span>
                            @if ($presensi->approved_at)
                                <p class="text-xs text-[#70211a]">
                                    Pada {{ $presensi->approved_at->isoFormat('dddd, D MMMM Y [pukul] HH:mm') }} WIB
                                </p>
                            @endif
                            @if ($presensi->catatan_kepsek)
                                <p class="text-xs font-medium text-teks pt-1 border-t border-merah/20 mt-1">
                                    <strong>Alasan Penolakan:</strong> "{{ $presensi->catatan_kepsek }}"
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="p-3.5 rounded-2xl bg-emas-muda border border-emas/40 text-teks flex items-center gap-3">
                            <span class="text-xl">⏳</span>
                            <div class="text-xs leading-relaxed">
                                <strong class="text-biru-tua block font-bold">Status: Menunggu Persetujuan</strong>
                                Silakan tinjau alasan di atas, lalu tentukan persetujuan Anda melalui formulir di bawah.
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Form Aksi Persetujuan (Hanya muncul jika status masih menunggu atau ingin diperbarui) -->
            @if ($presensi->approval_status === 'menunggu')
                <div class="p-5 sm:p-6 bg-[#faf7f0] border-t border-garis">
                    <form method="POST" action="{{ route('izin.approval.proses', ['token' => $presensi->token_izin]) }}" class="space-y-4"
                          onsubmit="return confirmAksi(event)">
                        @csrf

                        <div>
                            <label for="catatan_kepsek" class="block text-[11px] font-bold text-teks uppercase tracking-wider mb-1.5">
                                Catatan Kepala Sekolah <span class="text-redup font-normal lowercase">(opsional)</span>
                            </label>
                            <textarea id="catatan_kepsek" name="catatan_kepsek" rows="2"
                                placeholder="Contoh: Disetujui, harap titipkan tugas untuk kelas yang ditinggalkan..."
                                class="w-full rounded-2xl border border-garis text-xs sm:text-sm p-3 focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none bg-white transition-all"></textarea>
                        </div>

                        <input type="hidden" name="aksi" id="aksiInput" value="setujui">

                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <button type="submit" onclick="document.getElementById('aksiInput').value='tolak'"
                                class="w-full py-3 px-4 bg-white text-merah border-2 border-merah/30 hover:bg-merah hover:text-white hover:border-merah text-xs sm:text-sm font-bold rounded-2xl shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                <span>❌</span>
                                <span>Tolak Izin</span>
                            </button>

                            <button type="submit" onclick="document.getElementById('aksiInput').value='setujui'"
                                class="w-full py-3 px-4 bg-hijau hover:bg-[#25633e] text-white text-xs sm:text-sm font-bold rounded-2xl shadow-sm hover:shadow transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                <span>✅</span>
                                <span>Setujui Izin</span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Tautan Navigasi Bawah -->
        <div class="text-center pt-2 space-y-2">
            <p class="text-[11px] text-redup">
                Sistem Presensi Guru • SMK Islam Al-Amanah Salem, Brebes
            </p>
            <div>
                <a href="{{ route('login') }}" class="text-xs font-semibold text-biru hover:text-biru-tua underline">
                    Buka Halaman Login Dashboard Presensi →
                </a>
            </div>
        </div>

    </div>

    <script>
        function confirmAksi(e) {
            const aksi = document.getElementById('aksiInput').value;
            const pesan = aksi === 'setujui'
                ? 'Apakah Anda yakin ingin MENYETUJUI permohonan izin ini?'
                : 'Apakah Anda yakin ingin MENOLAK permohonan izin ini?';
            return confirm(pesan);
        }
    </script>
</body>
</html>

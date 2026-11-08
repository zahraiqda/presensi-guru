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
                        Data Guru & Tenaga Pendidik
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-200 max-w-xl">
                        Kelola data guru, akun akses presensi mobile/web, dan import massal data pengajar sekolah.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="px-5 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center shadow-inner">
                        <p class="text-[11px] text-slate-200 font-medium uppercase tracking-wider">Total Guru</p>
                        <p class="text-xl sm:text-2xl font-black text-emas-terang">{{ $guru->total() }}</p>
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

        <!-- Action & Search Bar Card -->
        <div class="bg-white rounded-2xl border border-garis p-5 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- Search Input -->
                <form method="GET" class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-redup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari NIK, Nama, atau Guru Mapel..."
                        class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all placeholder-redup/70 bg-white">
                    @if ($keyword)
                        <a href="{{ route('admin.guru.index') }}" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-redup hover:text-teks text-xs font-semibold">
                            ✕
                        </a>
                    @endif
                </form>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <button type="button" onclick="document.getElementById('modal-import').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-biru-muda border border-biru/20 text-biru-tua text-sm font-semibold rounded-xl hover:bg-biru/10 transition-all shadow-xs">
                        <svg class="w-4 h-4 text-biru" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span>Import CSV/Excel</span>
                    </button>

                    <a href="{{ route('admin.guru.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-biru text-emas-terang text-sm font-semibold rounded-xl hover:bg-biru-tua border border-emas/40 transition-all shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Guru</span>
                    </a>
                </div>
            </div>

            <!-- Petunjuk Import Alert -->
            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-emas-muda border border-emas/30 text-teks text-xs">
                <span class="text-base leading-none">💡</span>
                <div class="leading-relaxed">
                    <strong class="text-biru-tua font-bold">Petunjuk Import CSV:</strong> Gunakan file <code class="px-1.5 py-0.5 rounded bg-white border border-emas/40 font-mono text-biru-tua font-bold">.csv</code> dengan urutan kolom: pertama <strong>NIK</strong>, kedua <strong>Nama</strong>, ketiga <strong>Mata Pelajaran</strong> (opsional, contoh: <em>PPKn</em>). Baris pertama header akan dilewati otomatis. Username & kata sandi akun guru baru akan otomatis dibuat dari NIK.
                </div>
            </div>
        </div>

        <!-- Table Data Guru Card -->
        <div class="bg-white rounded-2xl border border-garis shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-biru-muda border-b border-garis text-biru-tua text-xs font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Guru</th>
                            <th class="px-6 py-4">NIK</th>
                            <th class="px-6 py-4">Akun Login</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-garis">
                        @forelse ($guru as $g)
                            <tr class="hover:bg-biru-muda/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-biru-tua text-emas-terang font-bold text-xs flex items-center justify-center shrink-0 border border-emas/30 shadow-xs">
                                            {{ strtoupper(substr($g->nama, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-teks leading-tight">{{ $g->nama }}</p>
                                            <div class="mt-1 flex items-center gap-1.5">
                                                @if ($g->mata_pelajaran)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-biru-muda text-biru text-[11px] font-semibold border border-biru/20">
                                                        <svg class="w-3 h-3 text-biru shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                        </svg>
                                                        {{ $g->label_mapel }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-redup">Tenaga Pengajar</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-krem text-teks font-mono text-xs font-medium border border-garis">
                                        {{ $g->nik }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-xs text-redup">
                                        <svg class="w-3.5 h-3.5 text-redup" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="font-mono font-medium text-teks">{{ $g->user->username ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.guru.edit', $g) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-biru-muda text-biru-tua hover:bg-biru/15 font-semibold text-xs border border-biru/20 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        <form method="POST" action="{{ route('admin.guru.destroy', $g) }}" class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus guru {{ $g->nama }}? Akun login dan data riwayat guru ini akan terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-merah-muda text-merah hover:bg-merah/20 font-semibold text-xs border border-merah/20 transition-colors">
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
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="w-12 h-12 rounded-2xl bg-krem text-redup flex items-center justify-center mx-auto text-xl border border-garis">
                                            👥
                                        </div>
                                        <p class="font-semibold text-teks">Tidak ada data guru</p>
                                        <p class="text-xs text-redup">
                                            @if ($keyword)
                                                Tidak ditemukan guru dengan kata kunci "{{ $keyword }}". Coba cari kata kunci lain.
                                            @else
                                                Belum ada data guru yang tersimpan. Silakan tambah data baru atau import dari CSV.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($guru->hasPages())
                <div class="p-4 border-t border-garis bg-krem/50">
                    {{ $guru->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Modal Import CSV -->
    <div id="modal-import" class="hidden fixed inset-0 bg-biru-tua/70 backdrop-blur-xs flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-garis w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header -->
            <div class="p-6 bg-gradient-to-r from-biru-tua to-biru text-white relative border-b-2 border-emas">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white p-1 flex items-center justify-center ring-2 ring-emas shrink-0 shadow-sm">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-white">Import Data Guru</h3>
                        <p class="text-xs text-emas-terang">SMK Islam Al Amanah Salem</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')"
                    class="absolute top-5 right-5 text-white/70 hover:text-white transition-colors">
                    ✕
                </button>
            </div>

            <!-- Modal Form -->
            <form method="POST" action="{{ route('admin.guru.import') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-teks uppercase tracking-wider">
                        Pilih File CSV (.csv)
                    </label>
                    <div class="border-2 border-dashed border-garis rounded-2xl p-6 text-center hover:border-biru transition-colors bg-krem">
                        <svg class="w-10 h-10 text-biru mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <input type="file" name="file_csv" accept=".csv" required
                            class="block w-full text-xs text-redup file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-biru file:text-emas-terang hover:file:bg-biru-tua cursor-pointer">
                        <p class="text-[11px] text-redup mt-2">
                            Maksimal ukuran file: 2 MB
                        </p>
                    </div>
                    @error('file_csv')
                        <p class="text-merah text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="p-3 bg-biru-muda rounded-xl text-[11px] text-redup space-y-1 border border-garis">
                    <p>• Kolom 1: <strong>NIK</strong> (wajib diisi, unik)</p>
                    <p>• Kolom 2: <strong>Nama Lengkap Guru</strong> (wajib diisi)</p>
                    <p>• Kolom 3: <strong>Mata Pelajaran</strong> (opsional, contoh: PPKn, Matematika)</p>
                    <p>• Akun user dibuat otomatis dengan password default NIK</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')"
                        class="px-4 py-2.5 text-xs font-semibold text-redup hover:bg-krem rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-biru text-emas-terang text-xs font-semibold rounded-xl hover:bg-biru-tua border border-emas/40 shadow-xs transition-all">
                        Unggah & Proses
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
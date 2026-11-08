<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Hero Banner Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-biru-tua via-biru to-biru-tua text-white p-6 sm:p-8 shadow-xl border-b-4 border-emas">
            <!-- Subtle Watermark Logo -->
            <div class="absolute -right-8 -bottom-10 w-64 h-64 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo.png') }}" alt="Watermark Logo" class="w-full h-full object-contain">
            </div>

            <!-- Background Decorative Orbs & Grid -->
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-20 w-60 h-60 rounded-full bg-emas/20 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-emas-terang text-xs font-semibold tracking-wide">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-3.5 h-3.5 object-contain">
                        <span>SMK ISLAM AL AMANAH SALEM - BREBES</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                        Manajemen & Reset Password Kepala Sekolah
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-200 max-w-2xl">
                        Atur kredensial akun, perbarui nama pimpinan, dan lakukan reset kata sandi akses login Kepala Sekolah.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-2xl bg-white/10 border border-white/20 text-emas-terang text-xs font-bold tracking-wide">
                        👑 Hak Akses Administrator
                    </span>
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

        @forelse ($kepsekList as $kepsek)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start" x-data="{ showNewPw: false, showConfirmPw: false }">

                <!-- Profil Card (5 Cols) -->
                <div class="lg:col-span-5 bg-white rounded-3xl border border-garis p-6 shadow-xs space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-emas text-biru-tua font-black text-2xl flex items-center justify-center shrink-0 shadow-md border border-emas/40">
                            {{ strtoupper(substr($kepsek->nama, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emas-muda text-teks border border-emas/30 text-[11px] font-bold tracking-wide mb-1">
                                👑 Kepala Sekolah
                            </span>
                            <h2 class="text-lg font-bold text-biru-tua leading-snug">
                                {{ $kepsek->nama }}
                            </h2>
                            <p class="text-xs text-redup font-mono mt-0.5">
                                {{ '@' . $kepsek->username }}
                            </p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-krem border border-garis space-y-2 text-xs">
                        <div class="flex justify-between items-center text-redup">
                            <span>Status Akun:</span>
                            <span class="font-bold text-hijau bg-hijau-muda border border-hijau/20 px-2 py-0.5 rounded-md">Aktif</span>
                        </div>
                        <div class="flex justify-between items-center text-redup">
                            <span>Username Login:</span>
                            <span class="font-mono font-semibold text-teks">{{ $kepsek->username }}</span>
                        </div>
                        <div class="flex justify-between items-center text-redup">
                            <span>Email:</span>
                            <span class="font-mono text-teks">{{ $kepsek->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-redup">
                            <span>No. WhatsApp Kepsek:</span>
                            <span class="font-mono font-bold text-biru">{{ $pengaturan->no_wa_kepsek ?? 'Belum Diatur' }}</span>
                        </div>
                    </div>

                    <!-- Form Update Profil Nama & Username -->
                    <div class="pt-2 border-t border-garis">
                        <h3 class="text-xs font-bold text-biru-tua uppercase tracking-wider mb-3">
                            Perbarui Data Profil Pimpinan
                        </h3>
                        <form method="POST" action="{{ route('admin.kepsek.profile', $kepsek) }}" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-teks">Nama Lengkap & Gelar</label>
                                <input type="text" name="nama" value="{{ old('nama', $kepsek->nama) }}" required
                                    class="w-full px-3.5 py-2 rounded-xl border border-garis text-sm focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none bg-white text-teks">
                                @error('nama')
                                    <p class="text-merah text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-teks">Username Login</label>
                                <input type="text" name="username" value="{{ old('username', $kepsek->username) }}" required
                                    class="w-full px-3.5 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none bg-white text-teks">
                                @error('username')
                                    <p class="text-merah text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-semibold text-teks">Nomor WhatsApp Kepala Sekolah</label>
                                    <span class="text-[10px] text-redup">Untuk link persetujuan izin</span>
                                </div>
                                <input type="text" name="no_wa_kepsek" value="{{ old('no_wa_kepsek', $pengaturan->no_wa_kepsek) }}"
                                    placeholder="Contoh: 08123456789 atau 628123456789"
                                    class="w-full px-3.5 py-2 rounded-xl border border-garis text-sm font-mono focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none bg-white text-teks">
                                <p class="text-[11px] text-redup">Nomor tujuan chat WhatsApp saat guru mengajukan izin.</p>
                                @error('no_wa_kepsek')
                                    <p class="text-merah text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end pt-1">
                                <button type="submit"
                                    class="px-4 py-2 bg-biru-muda hover:bg-biru/10 text-biru-tua text-xs font-semibold rounded-xl border border-biru/20 transition-colors">
                                    Simpan Perubahan Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reset Password Card (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-garis p-6 sm:p-7 shadow-xs space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-garis">
                        <div class="w-10 h-10 rounded-xl bg-biru-tua text-emas-terang flex items-center justify-center text-lg font-bold border border-emas/30 shadow-xs">
                            🔒
                        </div>
                        <div>
                            <h3 class="font-bold text-biru-tua text-base leading-tight">
                                Formulir Reset Password Kepala Sekolah
                            </h3>
                            <p class="text-xs text-redup mt-0.5">
                                Masukkan kata sandi baru untuk akun login <strong class="text-teks">{{ $kepsek->nama }}</strong>.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.kepsek.password', $kepsek) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Password Baru -->
                        <div class="space-y-1.5">
                            <label for="password" class="block text-xs font-bold text-teks uppercase tracking-wider">
                                Kata Sandi Baru <span class="text-merah">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showNewPw ? 'text' : 'password'"
                                       id="password"
                                       name="password"
                                       required
                                       placeholder="Minimal 6 karakter"
                                       class="w-full pl-4 pr-11 py-2.5 rounded-xl border border-garis text-sm focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all font-mono bg-white text-teks placeholder-redup/70">
                                <button type="button"
                                        @click="showNewPw = !showNewPw"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-redup hover:text-teks text-sm">
                                    <span x-text="showNewPw ? '🙈' : '👁️'"></span>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-merah text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="block text-xs font-bold text-teks uppercase tracking-wider">
                                Konfirmasi Kata Sandi Baru <span class="text-merah">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showConfirmPw ? 'text' : 'password'"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required
                                       placeholder="Ulangi kata sandi baru"
                                       class="w-full pl-4 pr-11 py-2.5 rounded-xl border border-garis text-sm focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all font-mono bg-white text-teks placeholder-redup/70">
                                <button type="button"
                                        @click="showConfirmPw = !showConfirmPw"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-redup hover:text-teks text-sm">
                                    <span x-text="showConfirmPw ? '🙈' : '👁️'"></span>
                                </button>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-emas-muda border border-emas/30 text-xs text-teks flex items-start gap-3">
                            <span class="text-base leading-none">💡</span>
                            <div class="leading-relaxed">
                                <strong class="text-biru-tua font-bold">Petunjuk Keamanan:</strong>
                                Setelah password berhasil direset, silakan sampaikan kata sandi baru ini secara langsung atau melalui saluran komunikasi resmi kepada Kepala Sekolah. Kepala Sekolah juga dapat memperbarui passwordnya secara mandiri pada menu Profil.
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-garis">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-biru text-emas-terang font-semibold text-xs rounded-xl hover:bg-biru-tua border border-emas/40 shadow-xs transition-all uppercase tracking-wider">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                <span>Reset Password Kepala Sekolah</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        @empty
            <div class="bg-white rounded-3xl border border-garis p-12 text-center max-w-md mx-auto space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-emas-muda text-emas flex items-center justify-center mx-auto text-2xl border border-emas/30">
                    👑
                </div>
                <h3 class="font-bold text-teks text-base">Belum Ada Akun Kepala Sekolah</h3>
                <p class="text-xs text-redup">
                    Akun pengguna dengan peran Kepala Sekolah belum terdaftar di database sistem.
                </p>
            </div>
        @endforelse

    </div>
</x-app-layout>

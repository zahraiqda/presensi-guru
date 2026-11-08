<x-app-layout>
    <div class="space-y-6" x-data="{ showCurrentPw: false, showNewPw: false, showConfirmPw: false }">

        <!-- Hero Profile Header Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-biru-tua via-biru to-biru-tua text-white p-6 sm:p-8 shadow-xl border-b-4 border-emas">
            <!-- Subtle Watermark Logo -->
            <div class="absolute -right-10 -bottom-10 w-64 h-64 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo.png') }}" alt="Watermark Logo" class="w-full h-full object-contain">
            </div>

            <!-- Background Decorative Orbs & Grid -->
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-20 w-60 h-60 rounded-full bg-emas/20 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-5">
                <!-- Info -->
                <div class="space-y-1.5 min-w-0">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-emas-terang text-xs font-semibold tracking-wide">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight truncate">
                        {{ $user->nama }}
                    </h1>
                    <p class="text-sm text-slate-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ '@' . $user->username }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Status Alert -->
        @if (session('status'))
            <div class="flex items-center gap-3 p-4 rounded-2xl bg-biru-muda border border-biru/20 text-biru-tua shadow-xs animate-[fadeIn_0.3s_ease-out]">
                <div class="w-8 h-8 rounded-xl bg-biru text-emas-terang flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium">{{ session('status') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Left Column: Data Diri Card -->
            <div class="bg-white rounded-2xl shadow-xs border border-garis overflow-hidden">
                <div class="px-6 py-4 border-b border-garis bg-biru-muda/40">
                    <h3 class="font-bold text-biru-tua flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-biru text-emas-terang flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        Informasi Akun
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-0 divide-y divide-garis">
                        <!-- Nama -->
                        <div class="flex items-center justify-between py-3.5 first:pt-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-biru-muda flex items-center justify-center">
                                    <svg class="w-4.5 h-4.5 text-biru-tua" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-redup">Nama Lengkap</span>
                            </div>
                            <span class="text-sm font-semibold text-teks text-right">{{ $user->nama }}</span>
                        </div>

                        <!-- Username -->
                        <div class="flex items-center justify-between py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-biru-muda flex items-center justify-center">
                                    <svg class="w-4.5 h-4.5 text-biru-tua" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-redup">Username</span>
                            </div>
                            <span class="text-sm font-mono font-semibold text-teks bg-krem border border-garis px-2.5 py-1 rounded-lg">{{ $user->username }}</span>
                        </div>

                        <!-- Role -->
                        <div class="flex items-center justify-between py-3.5 last:pb-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emas-muda flex items-center justify-center">
                                    <svg class="w-4.5 h-4.5 text-emas" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-redup">Role / Jabatan</span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full
                                @if($user->role === 'admin') bg-biru-muda text-biru-tua border border-biru/20
                                @elseif($user->role === 'kepala_sekolah') bg-emas-muda text-teks border border-emas/30
                                @else bg-hijau-muda text-hijau border border-hijau/20
                                @endif">
                                <span class="w-1.5 h-1.5 rounded-full
                                    @if($user->role === 'admin') bg-biru
                                    @elseif($user->role === 'kepala_sekolah') bg-emas
                                    @else bg-hijau
                                    @endif"></span>
                                {{ ucwords(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>
                    </div>
                </div>

                @if ($user->role === 'admin' && isset($adminResetEmail))
                <!-- Email Reset Section (Admin only) -->
                <div class="px-6 pb-6">
                    <div class="rounded-xl bg-emas-muda border border-emas/30 p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center shrink-0 mt-0.5 border border-emas/40">
                                <svg class="w-4.5 h-4.5 text-biru-tua" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wider text-biru-tua mb-1">Email Reset Password</p>
                                <p class="text-sm font-semibold text-teks break-all">{{ $adminResetEmail }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Ganti Password Card -->
            <div class="bg-white rounded-2xl shadow-xs border border-garis overflow-hidden">
                <div class="px-6 py-4 border-b border-garis bg-biru-muda/40">
                    <h3 class="font-bold text-biru-tua flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-biru text-emas-terang flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        Ganti Password
                    </h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Password Saat Ini -->
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-teks mb-1.5">Password Saat Ini</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-redup" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input id="current_password"
                                       name="current_password"
                                       :type="showCurrentPw ? 'text' : 'password'"
                                       class="block w-full pl-10 pr-10 py-2.5 rounded-xl border border-garis bg-white text-sm text-teks placeholder-redup/70 focus:border-biru focus:ring-2 focus:ring-biru/20 transition-all outline-none"
                                       placeholder="Masukkan password saat ini"
                                       autocomplete="current-password"
                                       required>
                                <button type="button" @click="showCurrentPw = !showCurrentPw" class="absolute inset-y-0 right-0 pr-3 flex items-center text-redup hover:text-teks transition-colors">
                                    <svg x-show="!showCurrentPw" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showCurrentPw" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('current_password')" class="mt-1.5" />
                        </div>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-garis"></div></div>
                            <div class="relative flex justify-center"><span class="bg-white px-3 text-xs text-redup font-medium">Password Baru</span></div>
                        </div>

                        <!-- Password Baru -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-teks mb-1.5">Password Baru</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-redup" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </div>
                                <input id="password"
                                       name="password"
                                       :type="showNewPw ? 'text' : 'password'"
                                       class="block w-full pl-10 pr-10 py-2.5 rounded-xl border border-garis bg-white text-sm text-teks placeholder-redup/70 focus:border-biru focus:ring-2 focus:ring-biru/20 transition-all outline-none"
                                       placeholder="Minimal 6 karakter"
                                       autocomplete="new-password"
                                       required>
                                <button type="button" @click="showNewPw = !showNewPw" class="absolute inset-y-0 right-0 pr-3 flex items-center text-redup hover:text-teks transition-colors">
                                    <svg x-show="!showNewPw" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showNewPw" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                        </div>

                        <!-- Ulangi Password Baru -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-teks mb-1.5">Ulangi Password Baru</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-redup" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <input id="password_confirmation"
                                       name="password_confirmation"
                                       :type="showConfirmPw ? 'text' : 'password'"
                                       class="block w-full pl-10 pr-10 py-2.5 rounded-xl border border-garis bg-white text-sm text-teks placeholder-redup/70 focus:border-biru focus:ring-2 focus:ring-biru/20 transition-all outline-none"
                                       placeholder="Ketik ulang password baru"
                                       autocomplete="new-password"
                                       required>
                                <button type="button" @click="showConfirmPw = !showConfirmPw" class="absolute inset-y-0 right-0 pr-3 flex items-center text-redup hover:text-teks transition-colors">
                                    <svg x-show="!showConfirmPw" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showConfirmPw" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full py-3 px-4 rounded-xl text-white font-bold text-sm bg-biru hover:bg-biru-tua shadow-lg shadow-biru/20 border border-emas/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-emas-terang" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Simpan Password Baru</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
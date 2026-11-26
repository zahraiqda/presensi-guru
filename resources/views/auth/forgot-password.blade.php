<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-krem text-teks relative overflow-hidden"
         x-data="{
             cooldown: {{ $cooldownRemaining ?? 0 }},
             timer: null,
             init() {
                 if (this.cooldown > 0) {
                     this.startTimer();
                 }
             },
             startTimer() {
                 if (this.timer) clearInterval(this.timer);
                 this.timer = setInterval(() => {
                     if (this.cooldown > 0) {
                         this.cooldown--;
                     } else {
                         clearInterval(this.timer);
                     }
                 }, 1000);
             }
         }">
        <!-- Ambient Decorative Elements -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-biru/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-emas/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="w-full max-w-md p-8 sm:p-10 rounded-3xl bg-white border border-garis shadow-xl relative z-10 space-y-6">

            <div class="text-center space-y-2">
                <div class="w-16 h-16 rounded-2xl bg-white p-1.5 flex items-center justify-center shadow-md ring-2 ring-emas mx-auto mb-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Islam Al-Amanah" class="w-full h-full object-contain">
                </div>
                <h2 class="text-2xl font-extrabold text-biru-tua tracking-tight">Reset Password</h2>
                <p class="text-xs text-redup leading-relaxed">
                    Masukkan username akun Anda. Reset password mandiri via email hanya berlaku untuk akun <span class="text-biru font-semibold">Admin</span>. Untuk Guru / Kepsek, silakan hubungi Administrator sekolah.
                </p>
            </div>

            <!-- Cooldown Banner (Aktif jika dalam masa tunggu 60 detik) -->
            <div x-show="cooldown > 0" x-cloak
                 class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-start gap-3 animate-in fade-in duration-200">
                <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-xs mt-0.5">
                    ⏳
                </div>
                <div class="flex-1 space-y-1">
                    <p class="font-bold text-amber-900 text-sm">Waktu Cooldown Sedang Berjalan</p>
                    <p class="text-amber-800 leading-relaxed text-xs">
                        Link reset password hanya berlaku selama <strong>60 detik</strong>. Tombol kirim link dapat ditekan kembali dalam:
                    </p>
                    <div class="pt-1 flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-amber-200/80 border border-amber-300 font-mono font-bold text-sm text-amber-950">
                            <span x-text="cooldown"></span>&nbsp;detik
                        </span>
                        <span class="text-[11px] text-amber-700 italic">Harap periksa email Anda segera</span>
                    </div>
                </div>
            </div>

            @if (session('status'))
                <div class="p-3.5 rounded-xl bg-biru-muda border border-biru/20 text-biru-tua text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-emas shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('status_fallback'))
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 space-y-3">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="text-xs leading-relaxed">
                            <p class="font-bold text-amber-800">Koneksi Server Email (SMTP) Terkendala</p>
                            <p class="text-amber-700 mt-0.5">Jaringan/provider internet Anda memblokir port SMTP Gmail. Namun link reset password akun <strong>{{ session('status_fallback')['email'] }}</strong> berhasil dibuat secara aman (berlaku <strong>60 detik</strong>):</p>
                        </div>
                    </div>
                    <div class="pt-1">
                        <a href="{{ session('status_fallback')['url'] }}"
                           class="block w-full py-2.5 px-4 text-center rounded-xl bg-biru hover:bg-biru-tua text-emas-terang font-bold text-xs shadow-xs transition-all">
                            Buka Form Reset Password Sekarang (60 Detik) &rarr;
                        </a>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4"
                  @submit="if (cooldown === 0) { cooldown = 60; startTimer(); }">
                @csrf

                <div>
                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-teks mb-1.5">
                        Username
                    </label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-redup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="username"
                               type="text"
                               name="username"
                               value="{{ old('username') }}"
                               required
                               autofocus
                               placeholder="Masukkan username admin"
                               class="block w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-garis text-teks placeholder-redup/70 text-sm focus:border-biru focus:ring-2 focus:ring-biru/20 transition-all outline-none">
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="mt-2 text-merah" />
                </div>

                <div class="space-y-3 pt-2">
                    <button type="submit"
                            :disabled="cooldown > 0"
                            :class="cooldown > 0
                                ? 'bg-slate-200 text-slate-500 border-slate-300 cursor-not-allowed shadow-none'
                                : 'bg-biru hover:bg-biru-tua text-white border-emas/40 shadow-lg shadow-biru/20 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer'"
                            class="w-full py-3.5 px-4 rounded-xl font-bold text-sm border transition-all duration-200 flex items-center justify-center gap-2">
                        <template x-if="cooldown > 0">
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin text-slate-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Tunggu <span class="font-mono font-bold" x-text="cooldown"></span> detik untuk kirim ulang</span>
                            </span>
                        </template>
                        <template x-if="cooldown === 0">
                            <span>Kirim Link Reset Password</span>
                        </template>
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-biru hover:text-biru-tua transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Kembali ke Halaman Login</span>
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
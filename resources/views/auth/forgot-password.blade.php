<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-krem text-teks relative overflow-hidden">
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

            @if (session('status'))
                <div class="p-3.5 rounded-xl bg-biru-muda border border-biru/20 text-biru-tua text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-emas shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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
                            class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm bg-biru hover:bg-biru-tua shadow-lg shadow-biru/20 border border-emas/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Kirim Link Reset Password</span>
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
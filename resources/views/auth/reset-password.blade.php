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
                <h2 class="text-2xl font-extrabold text-biru-tua tracking-tight">Buat Password Baru</h2>
                <p class="text-xs text-redup leading-relaxed">
                    Masukkan password baru untuk akun Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-teks mb-1.5">
                        Email
                    </label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-redup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email', $request->email) }}"
                               required
                               autofocus
                               autocomplete="username"
                               class="block w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-garis text-teks placeholder-redup/70 text-sm focus:border-biru focus:ring-2 focus:ring-biru/20 transition-all outline-none">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-merah" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-teks mb-1.5">
                        Password Baru
                    </label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-redup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder="Minimal 8 karakter"
                               class="block w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-garis text-teks placeholder-redup/70 text-sm focus:border-biru focus:ring-2 focus:ring-biru/20 transition-all outline-none">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-merah" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-teks mb-1.5">
                        Ulangi Password Baru
                    </label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-redup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input id="password_confirmation"
                               type="password"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="Ketik ulang password baru"
                               class="block w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-garis text-teks placeholder-redup/70 text-sm focus:border-biru focus:ring-2 focus:ring-biru/20 transition-all outline-none">
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-merah" />
                </div>

                <div class="space-y-3 pt-2">
                    <button type="submit"
                            class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-sm bg-biru hover:bg-biru-tua shadow-lg shadow-biru/20 border border-emas/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-emas-terang" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Reset Password</span>
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

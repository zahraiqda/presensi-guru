<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Header Card -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-biru-tua via-biru to-biru-tua text-white p-6 sm:p-7 shadow-xl border-b-4 border-emas">
            <!-- Subtle Watermark Logo -->
            <div class="absolute -right-6 -bottom-6 w-44 h-44 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo.png') }}" alt="Watermark Logo" class="w-full h-full object-contain">
            </div>

            <div class="relative z-10 flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-emas-terang text-xs font-semibold tracking-wide">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-3.5 h-3.5 object-contain">
                        <span>SMK ISLAM AL AMANAH</span>
                    </div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-white leading-tight">
                        Edit Data Guru
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-200">
                        Perbarui informasi pengajar atau ganti password akun login guru <span class="text-emas-terang font-semibold">{{ $guru->nama }}</span>.
                    </p>
                </div>

                <a href="{{ route('admin.guru.index') }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-xs border border-white/20 transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl border border-garis p-6 sm:p-8 shadow-xs">
            <form method="POST" action="{{ route('admin.guru.update', $guru) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- NIK -->
                    <div class="space-y-1.5" x-data="{ nikVal: '{{ old('nik', $guru->nik) }}' }">
                        <div class="space-y-1">
                            <label for="nik" class="block text-xs font-bold text-teks uppercase tracking-wider">
                                Nomor Induk Kependudukan (NIK) <span class="text-merah">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 text-[11px] font-mono font-semibold px-2 py-0.5 rounded-md border transition-all"
                                    :class="nikVal.length >= 16
                                        ? 'bg-hijau-muda text-hijau border-hijau/30'
                                        : (nikVal.length > 0 ? 'bg-merah-muda text-merah border-merah/30' : 'bg-krem text-redup border-garis')">
                                    <span x-text="nikVal.length"></span>/16 digit
                                    <span x-show="nikVal.length >= 16">✓</span>
                                </span>
                                <span class="text-[11px] text-redup" x-show="nikVal.length === 0">
                                    Wajib minimal 16 digit angka
                                </span>
                                <span class="text-[11px] text-merah font-medium" x-show="nikVal.length > 0 && nikVal.length < 16" x-cloak>
                                    Kurang <span x-text="16 - nikVal.length"></span> digit lagi
                                </span>
                                <span class="text-[11px] text-hijau font-semibold" x-show="nikVal.length >= 16" x-cloak>
                                    ✓ Format NIK valid
                                </span>
                            </div>
                        </div>
                        <input id="nik" name="nik" type="text"
                            x-model="nikVal"
                            @input="nikVal = $event.target.value.replace(/[^0-9]/g, '')"
                            inputmode="numeric"
                            minlength="16"
                            maxlength="20"
                            required autofocus
                            placeholder="Contoh: 3329012345678901"
                            class="w-full px-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 font-mono outline-none transition-all placeholder-redup/70 bg-white">
                        <x-input-error :messages="$errors->get('nik')" class="mt-1" />
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="space-y-1.5">
                        <div class="space-y-1">
                            <label for="nama" class="block text-xs font-bold text-teks uppercase tracking-wider">
                                Nama Lengkap Guru <span class="text-merah">*</span>
                            </label>
                            <div class="text-[11px] text-redup py-0.5">
                                Sertakan gelar pendidikan bila ada (contoh: S.Pd.)
                            </div>
                        </div>
                        <input id="nama" name="nama" type="text" value="{{ old('nama', $guru->nama) }}" required
                            placeholder="Contoh: Ahmad Fauzi, S.Pd."
                            class="w-full px-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all placeholder-redup/70 bg-white">
                        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                    </div>
                </div>

                <!-- Mata Pelajaran / Bidang Studi -->
                <div class="space-y-2 p-4 sm:p-5 rounded-2xl bg-biru-muda/30 border border-biru/15">
                    <div class="flex items-center justify-between">
                        <label for="mata_pelajaran" class="block text-xs font-bold text-biru-tua uppercase tracking-wider">
                            Mata Pelajaran / Tugas Mengajar
                        </label>
                        <span class="text-[11px] text-redup">Bisa pilih atau ketik bebas</span>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-redup">
                            <svg class="w-4 h-4 text-biru" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <input id="mata_pelajaran" name="mata_pelajaran" type="text" list="daftar-mapel" value="{{ old('mata_pelajaran', $guru->mata_pelajaran) }}"
                            placeholder="Contoh: PPKn, Matematika, Bahasa Indonesia..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all placeholder-redup/70 bg-white">
                    </div>
                    <datalist id="daftar-mapel">
                        <option value="PPKn">
                        <option value="Pendidikan Agama Islam">
                        <option value="Bahasa Indonesia">
                        <option value="Matematika">
                        <option value="Bahasa Inggris">
                        <option value="Ilmu Pengetahuan Alam (IPA)">
                        <option value="Ilmu Pengetahuan Sosial (IPS)">
                        <option value="Pendidikan Jasmani & Olahraga (PJOK)">
                        <option value="Seni Budaya">
                        <option value="Informatika">
                        <option value="Teknik Komputer & Jaringan (TKJ)">
                        <option value="Rekayasa Perangkat Lunak (RPL)">
                        <option value="Produk Kreatif & Kewirausahaan (PKK)">
                        <option value="Bimbingan Konseling (BK)">
                        <option value="Sejarah Indonesia">
                    </datalist>
                    <div class="flex flex-wrap items-center gap-1.5 pt-1">
                        <span class="text-[11px] text-redup mr-1 font-medium">Pilihan cepat:</span>
                        @foreach (['PPKn', 'Matematika', 'B. Indonesia', 'B. Inggris', 'PAI', 'PJOK', 'Informatika', 'TKJ', 'BK'] as $pilihan)
                            <button type="button" onclick="document.getElementById('mata_pelajaran').value='{{ $pilihan }}'"
                                class="px-2.5 py-1 rounded-lg text-xs bg-white hover:bg-biru hover:text-white text-biru-tua border border-biru/20 transition-all font-medium shadow-2xs">
                                + {{ $pilihan }}
                            </button>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('mata_pelajaran')" class="mt-1" />
                </div>

                <div class="p-5 rounded-2xl bg-krem border border-garis space-y-4">
                    <div class="flex items-center gap-2 text-biru-tua font-bold text-xs uppercase tracking-wider">
                        <svg class="w-4 h-4 text-emas" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Akun Login Guru</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Username -->
                        <div class="space-y-1.5">
                            <label for="username" class="block text-xs font-semibold text-teks">
                                Username <span class="text-merah">*</span>
                            </label>
                            <input id="username" name="username" type="text" value="{{ old('username', $guru->user->username) }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all placeholder-redup/70 bg-white">
                            <x-input-error :messages="$errors->get('username')" class="mt-1" />
                        </div>

                        <!-- Password Baru -->
                        <div class="space-y-1.5">
                            <label for="password" class="block text-xs font-semibold text-teks">
                                Password Baru
                            </label>
                            <input id="password" name="password" type="text"
                                placeholder="Kosongkan bila tidak diubah"
                                class="w-full px-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all placeholder-redup/70 bg-white font-mono">
                            <p class="text-[11px] text-redup mt-1">Kosongkan kolom password jika tidak ingin mengganti password lama.</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- Submit / Cancel -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-garis">
                    <a href="{{ route('admin.guru.index') }}"
                        class="px-5 py-2.5 rounded-xl text-redup hover:bg-krem text-xs font-semibold transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-biru text-emas-terang font-semibold text-xs rounded-xl hover:bg-biru-tua border border-emas/40 shadow-xs transition-all uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
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
                        Edit Data Guru & Staf
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-200">
                        Perbarui informasi atau ganti password akun login guru & staf <span class="text-emas-terang font-semibold">{{ $guru->nama }}</span>.
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
                                Nama Lengkap Guru & Staf <span class="text-merah">*</span>
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

                <!-- Jabatan / Tugas / Mata Pelajaran -->
                @php
                    $jabatanList = [
                        'Tugas Tambahan / Waka & Struktural' => [
                            'Waka Kurikulum',
                            'Waka Kesiswaan',
                            'Waka Hubin (Hubungan Industri)',
                            'Waka Sarpras (Sarana & Prasarana)',
                            'Kepala Program (Kaprog) TKJ',
                            'Kepala Program (Kaprog) RPL',
                            'Guru Bimbingan Konseling (BK)',
                            'Pembina OSIS',
                        ],
                        'Tenaga Kependidikan / Staf Sekolah' => [
                            'Staf Tata Usaha (TU)',
                            'Bendahara Sekolah',
                            'Operator Sekolah / Dapodik',
                            'Toolman / Teknisi Bengkel',
                            'Laboran Komputer / IPA',
                            'Pustakawan',
                            'Petugas Keamanan / Satpam',
                        ],
                        'Guru Mata Pelajaran' => [
                            'Guru Pendidikan Agama Islam (PAI)',
                            'Guru PPKn',
                            'Guru Bahasa Indonesia',
                            'Guru Matematika',
                            'Guru Bahasa Inggris',
                            'Guru Informatika',
                            'Guru Produktif TKJ',
                            'Guru Produktif RPL',
                            'Guru PJOK (Olahraga)',
                            'Guru Seni Budaya',
                            'Guru Sejarah Indonesia',
                            'Guru IPAS / IPA',
                            'Guru Produk Kreatif & Kewirausahaan (PKK)',
                        ],
                    ];
                    $flatList = array_merge(...array_values($jabatanList));
                    $currentVal = old('mata_pelajaran', $guru->mata_pelajaran);
                    $isPredefined = in_array($currentVal, $flatList);
                @endphp

                <div class="space-y-3 p-4 sm:p-5 rounded-2xl bg-biru-muda/30 border border-biru/15"
                     x-data="{
                         selectedOption: '{{ $isPredefined ? $currentVal : ($currentVal ? 'Lainnya' : '') }}',
                         customJabatan: '{{ $isPredefined ? '' : $currentVal }}'
                     }">
                    <div class="flex items-center justify-between">
                        <label for="select_jabatan" class="block text-xs font-bold text-biru-tua uppercase tracking-wider">
                            Jabatan / Mata Pelajaran
                        </label>
                        <span class="text-[11px] text-redup">Pilih peran guru atau staf dari dropdown</span>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-redup">
                            <svg class="w-4 h-4 text-biru" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <select id="select_jabatan" x-model="selectedOption"
                            @change="if (selectedOption !== 'Lainnya') customJabatan = selectedOption"
                            class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all bg-white cursor-pointer font-medium">
                            <option value="">-- Pilih Jabatan / Tugas Mengajar --</option>
                            @foreach ($jabatanList as $kategori => $daftar)
                                <optgroup label="📂 {{ $kategori }}">
                                    @foreach ($daftar as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                            <optgroup label="⚙️ Pilihan Lainnya">
                                <option value="Lainnya">➕ Lainnya (Ketik Manual...)</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Input Khusus jika memilih 'Lainnya' -->
                    <div x-show="selectedOption === 'Lainnya'" x-cloak class="space-y-1.5 pt-1">
                        <label for="custom_jabatan" class="block text-xs font-semibold text-biru-tua">
                            Ketik Nama Jabatan / Mapel Khusus:
                        </label>
                        <input type="text" id="custom_jabatan" x-model="customJabatan"
                            placeholder="Contoh: Koordinator BKK, Instruktur Kejuruan, dll."
                            class="w-full px-4 py-2.5 rounded-xl border border-garis text-sm text-teks focus:border-biru focus:ring-2 focus:ring-biru/20 outline-none transition-all bg-white placeholder-redup/70">
                    </div>

                    <!-- Hidden input yang dikirim ke backend -->
                    <input type="hidden" name="mata_pelajaran" :value="selectedOption === 'Lainnya' ? customJabatan : selectedOption">
                    <x-input-error :messages="$errors->get('mata_pelajaran')" class="mt-1" />
                </div>

                <div class="p-5 rounded-2xl bg-krem border border-garis space-y-4">
                    <div class="flex items-center gap-2 text-biru-tua font-bold text-xs uppercase tracking-wider">
                        <svg class="w-4 h-4 text-emas" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Akun Login Guru & Staf</span>
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
                        <span>Simpan Perubahan Guru & Staf</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
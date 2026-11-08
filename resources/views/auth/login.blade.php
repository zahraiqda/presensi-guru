@php
    $pengaturan = \App\Models\Pengaturan::first();

    $jamMasukMulai = $pengaturan->jam_masuk_mulai ?? '06:00:00';
    $jamMasukToleransi = $pengaturan->jam_masuk_toleransi ?? '07:15:00';
    $jamMasukSelesai = $pengaturan->jam_masuk_selesai ?? '12:00:00';
    $jamPulangMinimal = $pengaturan->jam_pulang_minimal ?? '15:00:00';
    $jamPulangSelesai = $pengaturan->jam_pulang_selesai ?? '19:00:00';

    $masukMulai = \Illuminate\Support\Carbon::parse($jamMasukMulai)->format('H.i');
    $masukToleransi = \Illuminate\Support\Carbon::parse($jamMasukToleransi)->format('H.i');
    $masukSelesai = \Illuminate\Support\Carbon::parse($jamMasukSelesai)->format('H.i');
    $pulangMulai = \Illuminate\Support\Carbon::parse($jamPulangMinimal)->format('H.i');
    $pulangSelesai = \Illuminate\Support\Carbon::parse($jamPulangSelesai)->format('H.i');

    $masukMulaiHM = \Illuminate\Support\Carbon::parse($jamMasukMulai)->format('H:i');
    $masukToleransiHM = \Illuminate\Support\Carbon::parse($jamMasukToleransi)->format('H:i');
    $masukSelesaiHM = \Illuminate\Support\Carbon::parse($jamMasukSelesai)->format('H:i');
    $pulangMulaiHM = \Illuminate\Support\Carbon::parse($jamPulangMinimal)->format('H:i');
    $pulangSelesaiHM = \Illuminate\Support\Carbon::parse($jamPulangSelesai)->format('H:i');

    $now = now();
    $nowHM = $now->format('H:i');
    $isSunday = $now->isSunday();

    if ($isSunday) {
        $initialStatusTeks = 'Presensi ditutup (Hari libur)';
        $initialStatusClass = 'tutup';
    } elseif ($nowHM < $masukMulaiHM) {
        $initialStatusTeks = 'Presensi belum dibuka';
        $initialStatusClass = 'tutup';
    } elseif ($nowHM <= $masukToleransiHM) {
        $initialStatusTeks = 'Presensi dibuka (Masuk)';
        $initialStatusClass = 'buka';
    } elseif ($nowHM <= $masukSelesaiHM) {
        $initialStatusTeks = 'Presensi dibuka (Masuk - Terlambat)';
        $initialStatusClass = 'terlambat';
    } elseif ($nowHM < $pulangMulaiHM) {
        $initialStatusTeks = 'Presensi ditutup';
        $initialStatusClass = 'tutup';
    } elseif ($nowHM <= $pulangSelesaiHM) {
        $initialStatusTeks = 'Presensi dibuka (Pulang)';
        $initialStatusClass = 'buka';
    } else {
        $initialStatusTeks = 'Presensi ditutup';
        $initialStatusClass = 'tutup';
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk - Presensi Guru SMK Islam Al-Amanah</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<style>
  :root {
    --biru: #1f3a6e;
    --biru-tua: #162a52;
    --biru-muda: #e8edf7;
    --emas: #c9951b;
    --emas-muda: #fbf1d8;
    --hijau: #2f7d4f;
    --merah: #b3392f;
    --krem: #f7f3ea;
    --garis: #e2d9c6;
    --teks: #2b2a27;
    --redup: #6d675b;
  }
  * { box-sizing: border-box; }
  body {
    margin: 0;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    color: var(--teks);
    background: var(--krem);
    min-height: 100vh;
    display: flex; flex-direction: column;
  }

  /* ---------- Header ---------- */
  header {
    background: var(--biru);
    color: #fff;
    border-bottom: 4px solid var(--emas);
  }
  .header-isi {
    max-width: 1180px; margin: 0 auto;
    padding: 14px 28px;
    display: flex; align-items: center; gap: 16px;
  }
  .logo {
    width: 54px; height: 54px; flex: none; border-radius: 50%;
    background: #fff; border: 2px solid var(--emas);
    display: grid; place-items: center;
    font-size: 9px; color: var(--redup); text-align: center; line-height: 1.15;
    overflow: hidden;
  }
  .logo img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
  .nama h1 { margin: 0; font-size: 19px; font-weight: 700; letter-spacing: .2px; }
  .nama p { margin: 2px 0 0; font-size: 13px; color: #cdd5ea; }
  .bismillah {
    margin-left: auto;
    font-family: "Amiri", serif; font-size: 24px; color: #f0d48a;
  }

  /* ---------- Isi ---------- */
  main {
    flex: 1;
    max-width: 1180px; width: 100%;
    margin: 0 auto;
    padding: 32px 28px 24px;
    display: grid;
    grid-template-columns: 1.25fr 1fr;
    gap: 32px;
    align-items: start;
  }

  /* kolom kiri */
  .kiri h2 { margin: 0 0 4px; font-size: 24px; color: var(--biru-tua); }
  .kiri > .sub { margin: 0 0 22px; color: var(--redup); }

  .waktu {
    display: flex; align-items: stretch;
    background: #fff; border: 1px solid var(--garis); border-radius: 8px;
    margin-bottom: 18px;
  }
  .waktu > div { padding: 14px 18px; }
  .waktu .jam { border-right: 1px solid var(--garis); min-width: 190px; }
  .waktu .label { font-size: 12px; text-transform: uppercase; letter-spacing: .6px; color: var(--redup); }
  .waktu .nilai { font-size: 28px; font-weight: 700; color: var(--biru-tua); font-variant-numeric: tabular-nums; }
  .waktu .tgl { font-size: 15px; font-weight: 600; margin-top: 4px; }
  .status-presensi {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    margin-top: 4px;
    line-height: 1.2;
  }
  .status-presensi .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
  }
  .status-presensi.buka {
    color: var(--hijau);
  }
  .status-presensi.buka .dot {
    background: var(--hijau);
    box-shadow: 0 0 0 2px #d2ebd8;
  }
  .status-presensi.terlambat {
    color: #8a6410;
  }
  .status-presensi.terlambat .dot {
    background: var(--emas);
    box-shadow: 0 0 0 2px var(--emas-muda);
  }
  .status-presensi.tutup {
    color: var(--merah);
  }
  .status-presensi.tutup .dot {
    background: var(--merah);
    box-shadow: 0 0 0 2px #fcd9d6;
  }

  .panel {
    background: #fff; border: 1px solid var(--garis); border-radius: 8px;
    margin-bottom: 18px; overflow: hidden;
  }
  .panel h3 {
    margin: 0; padding: 11px 18px;
    font-size: 14px; font-weight: 700; color: var(--biru-tua);
    background: var(--biru-muda); border-bottom: 1px solid var(--garis);
  }
  table { width: 100%; border-collapse: collapse; font-size: 14px; }
  td, th { padding: 9px 18px; text-align: left; }
  th { font-weight: 600; color: var(--redup); font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }
  tr + tr td { border-top: 1px solid #efe8d8; }
  .tag { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: 600; }
  .tag.hijau { background: #e1f1e7; color: var(--hijau); }
  .tag.emas { background: var(--emas-muda); color: #8a6410; }
  .tag.merah { background: #f7e1de; color: var(--merah); }

  ol { margin: 0; padding: 14px 18px 14px 38px; font-size: 14px; line-height: 1.7; }

  .pengumuman {
    background: var(--emas-muda);
    border: 1px solid #ecd9a0; border-left: 4px solid var(--emas);
    border-radius: 6px; padding: 12px 16px; font-size: 14px; line-height: 1.55;
  }
  .pengumuman b { color: #7a560a; }

  /* kolom kanan: form */
  .kartu {
    background: #fff;
    border: 1px solid var(--garis);
    border-top: 4px solid var(--biru);
    border-radius: 8px;
    padding: 28px 28px 24px;
    position: sticky; top: 24px;
  }
  .kartu h2 { margin: 0 0 4px; font-size: 22px; color: var(--biru-tua); }
  .kartu > p { margin: 0 0 20px; color: var(--redup); font-size: 14px; }

  .peran {
    display: grid; grid-template-columns: repeat(3, 1fr);
    border: 1px solid var(--garis); border-radius: 6px; overflow: hidden;
    margin-bottom: 18px;
  }
  .peran input { position: absolute; opacity: 0; pointer-events: none; }
  .peran label {
    text-align: center; padding: 9px 4px; font-size: 14px; cursor: pointer;
    background: #fff; color: var(--redup);
  }
  .peran label + input + label, .peran input + label + input + label { border-left: 1px solid var(--garis); }
  .peran input:checked + label { background: var(--biru); color: #fff; font-weight: 600; }
  .peran input:focus-visible + label { outline: 2px solid var(--emas); outline-offset: -2px; }

  .field { margin-bottom: 16px; }
  .field > label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; }
  .field input {
    width: 100%; padding: 11px 12px; font: inherit; color: var(--teks);
    background: #fdfcf9; border: 1px solid var(--garis); border-radius: 6px;
  }
  .field input:focus { outline: 2px solid var(--biru); outline-offset: 1px; border-color: var(--biru); background: #fff; }

  .pw-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }
  .pw-wrapper input {
    padding-right: 44px;
  }
  button.btn-toggle-pw {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    padding: 0;
    margin: 0;
    background: transparent;
    border: 0;
    border-radius: 4px;
    color: var(--redup);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: color 0.15s, background-color 0.15s;
  }
  button.btn-toggle-pw:hover {
    background: rgba(0, 0, 0, 0.05);
    color: var(--biru);
  }
  button.btn-toggle-pw:focus-visible {
    outline: 2px solid var(--biru);
    outline-offset: -1px;
  }
  button.btn-toggle-pw svg {
    width: 19px;
    height: 19px;
  }

  .baris { display: flex; justify-content: space-between; align-items: center; font-size: 14px; margin: 2px 0 20px; }
  .baris label { display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }
  .baris a { color: var(--biru); text-decoration: none; }
  .baris a:hover { text-decoration: underline; }

  button {
    width: 100%; padding: 12px; font: inherit; font-weight: 600;
    color: #fff; background: var(--biru); border: 0; border-radius: 6px; cursor: pointer;
    transition: background-color 0.15s ease;
  }
  button:hover { background: var(--biru-tua); }

  .bantuan {
    margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--garis);
    font-size: 13px; color: var(--redup); line-height: 1.5;
  }

  .pesan-error {
    background: #fde8e7;
    border: 1px solid #f8b4b0;
    color: var(--merah);
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 13px;
    margin-bottom: 16px;
    line-height: 1.45;
  }

  .pesan-sukses {
    background: var(--biru-muda);
    border: 1px solid #cbd7ee;
    color: var(--biru-tua);
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 13px;
    margin-bottom: 16px;
    line-height: 1.45;
  }

  footer {
    text-align: center; padding: 16px; font-size: 13px; color: var(--redup);
    border-top: 1px solid var(--garis);
  }

  @media (max-width: 900px) {
    main { grid-template-columns: 1fr; padding: 20px 16px; }
    .kartu { order: -1; position: static; }
    .bismillah { display: none; }
    .header-isi { padding: 12px 16px; }
  }
</style>
</head>
<body>

<header>
  <div class="header-isi">
    <div class="logo">
      <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Islam Al-Amanah">
    </div>
    <div class="nama">
      <h1>SMK Islam Al-Amanah</h1>
      <p>Salem, Kabupaten Brebes &middot; Sistem Presensi Guru</p>
    </div>
    <div class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
  </div>
</header>

<main>
  <!-- ========== Informasi ========== -->
  <section class="kiri">
    <h2>Presensi Guru dan Staf</h2>
    <p class="sub">Catat kehadiran setiap hari kerja langsung dari lingkungan sekolah.</p>

    <div class="waktu">
      <div class="jam">
        <div class="label">Waktu sekarang</div>
        <div class="nilai" id="jam">--.--.--</div>
      </div>
      <div>
        <div class="label">Hari ini</div>
        <div class="tgl" id="tanggal">-</div>
        <div id="status-presensi" class="status-presensi {{ $initialStatusClass }}">
          <span class="dot"></span>
          <span id="status-teks">{{ $initialStatusTeks }}</span>
        </div>
      </div>
    </div>

    <div class="panel">
      <h3>Jadwal presensi</h3>
      <table>
        <tr><th>Sesi</th><th>Waktu</th><th>Keterangan</th></tr>
        <tr><td>Masuk</td><td>{{ $masukMulai }} &ndash; {{ $masukToleransi }}</td><td><span class="tag hijau">Tepat waktu</span></td></tr>
        <tr><td>Masuk</td><td>{{ $masukToleransi }} &ndash; {{ $masukSelesai }}</td><td><span class="tag emas">Terlambat</span></td></tr>
        <tr><td>Pulang</td><td>{{ $pulangMulai }} &ndash; {{ $pulangSelesai }}</td><td><span class="tag hijau">Presensi pulang</span></td></tr>
        <tr><td>Di luar jam</td><td>&mdash;</td><td><span class="tag merah">Tidak tercatat</span></td></tr>
      </table>
    </div>

    <div class="panel">
      <h3>Cara presensi</h3>
      <ol>
        <li>Masuk dengan akun Anda, pilih peran yang sesuai.</li>
        <li>Aktifkan lokasi (GPS) di perangkat Anda.</li>
        <li>Pastikan Anda berada di area sekolah, lalu tekan <b>Presensi Masuk</b>.</li>
        <li>Saat pulang, tekan <b>Presensi Pulang</b>.</li>
      </ol>
    </div>

    <div class="pengumuman">
      <b>Pengumuman:</b> Guru yang berhalangan hadir mohon mengajukan izin melalui menu <b>Izin / Sakit</b> sebelum pukul {{ $masukToleransi }}.
    </div>
  </section>

  <!-- ========== Form ========== -->
  <form class="kartu" method="POST" action="{{ route('login') }}">
    @csrf
    <h2>Masuk</h2>
    <p>Pilih peran, lalu masukkan akun Anda.</p>

    @if (session('status'))
      <div class="pesan-sukses">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="pesan-error">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <div class="peran" role="radiogroup" aria-label="Peran akun">
      <input type="radio" name="role" id="p-guru" value="guru" {{ old('role', 'guru') === 'guru' ? 'checked' : '' }}>
      <label for="p-guru">Guru</label>
      <input type="radio" name="role" id="p-kepsek" value="kepala_sekolah" {{ old('role') === 'kepala_sekolah' ? 'checked' : '' }}>
      <label for="p-kepsek">Kepala Sekolah</label>
      <input type="radio" name="role" id="p-admin" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}>
      <label for="p-admin">Admin</label>
    </div>

    <div class="field">
      <label for="username">Username</label>
      <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" required autofocus>
    </div>

    <div class="field">
      <label for="password">Password</label>
      <div class="pw-wrapper">
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        <button type="button" class="btn-toggle-pw" onclick="togglePassword()" id="btn-toggle-pw" title="Lihat password" aria-label="Lihat password">
          <svg id="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
        </button>
      </div>
    </div>

    <div class="baris">
      <label><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Ingat saya</label>
      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}">Lupa password?</a>
      @endif
    </div>

    <button type="submit">Masuk</button>

    <div class="bantuan">
      Lupa username atau akun terkunci? Hubungi admin sekolah (ruang TU).
    </div>
  </form>
</main>

<footer>&copy; {{ date('Y') }} SMK Islam Al-Amanah Salem</footer>

<script>
  const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
  const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
  const p = n => String(n).padStart(2, '0');

  const jadwal = {
    masukMulai: '{{ $masukMulaiHM }}',
    masukToleransi: '{{ $masukToleransiHM }}',
    masukSelesai: '{{ $masukSelesaiHM }}',
    pulangMulai: '{{ $pulangMulaiHM }}',
    pulangSelesai: '{{ $pulangSelesaiHM }}'
  };

  function updatePresensiStatus(d) {
    const el = document.getElementById('status-presensi');
    const txt = document.getElementById('status-teks');
    if (!el || !txt) return;

    const day = d.getDay(); // 0 = Minggu
    const hh = p(d.getHours());
    const mm = p(d.getMinutes());
    const hm = `${hh}:${mm}`;

    if (day === 0) {
      el.className = 'status-presensi tutup';
      txt.textContent = 'Presensi ditutup (Hari libur)';
      return;
    }

    if (hm < jadwal.masukMulai) {
      el.className = 'status-presensi tutup';
      txt.textContent = 'Presensi belum dibuka';
    } else if (hm <= jadwal.masukToleransi) {
      el.className = 'status-presensi buka';
      txt.textContent = 'Presensi dibuka (Masuk)';
    } else if (hm <= jadwal.masukSelesai) {
      el.className = 'status-presensi terlambat';
      txt.textContent = 'Presensi dibuka (Masuk - Terlambat)';
    } else if (hm < jadwal.pulangMulai) {
      el.className = 'status-presensi tutup';
      txt.textContent = 'Presensi ditutup';
    } else if (hm <= jadwal.pulangSelesai) {
      el.className = 'status-presensi buka';
      txt.textContent = 'Presensi dibuka (Pulang)';
    } else {
      el.className = 'status-presensi tutup';
      txt.textContent = 'Presensi ditutup';
    }
  }

  function tick() {
    const d = new Date();
    document.getElementById('jam').textContent = p(d.getHours()) + '.' + p(d.getMinutes()) + '.' + p(d.getSeconds());
    document.getElementById('tanggal').textContent = hari[d.getDay()] + ', ' + d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
    updatePresensiStatus(d);
  }
  tick(); setInterval(tick, 1000);

  function togglePassword() {
    const pw = document.getElementById('password');
    const eye = document.getElementById('eye-icon');
    const btn = document.getElementById('btn-toggle-pw');
    if (pw.type === 'password') {
      pw.type = 'text';
      btn.title = 'Sembunyikan password';
      btn.setAttribute('aria-label', 'Sembunyikan password');
      eye.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
    } else {
      pw.type = 'password';
      btn.title = 'Lihat password';
      btn.setAttribute('aria-label', 'Lihat password');
      eye.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
    }
  }
</script>
</body>
</html>
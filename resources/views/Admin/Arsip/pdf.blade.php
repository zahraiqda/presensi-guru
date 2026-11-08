<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arsip Presensi - {{ $arsip->nama_label }}</title>
    <style>
        @page {
            margin: 24px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }

        /* Kop Surat Styles */
        .kop-nss-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .kop-nss-table td {
            border: none;
            padding: 0;
        }
        .nss-box-table {
            border-collapse: collapse;
            margin-left: auto;
        }
        .nss-label {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5px;
            font-weight: bold;
            padding-right: 4px;
            letter-spacing: 0.5px;
            border: none !important;
            vertical-align: middle;
            color: #000;
        }
        .nss-cell {
            border: 1px solid #000 !important;
            width: 11px;
            height: 13px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5px;
            font-weight: bold;
            padding: 0 1px;
            vertical-align: middle;
            color: #000;
        }
        .kop-main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .kop-main-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .kop-logo-cell {
            width: 75px;
            text-align: center;
            vertical-align: middle;
        }
        .kop-logo-cell img {
            width: 72px;
            height: auto;
        }
        .kop-text-cell {
            text-align: center;
            padding: 0 4px;
        }
        .kop-spacer-cell {
            width: 75px;
        }
        .kop-instansi {
            font-family: Tahoma, Arial, Helvetica, sans-serif;
            font-size: 13px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #000;
        }
        .kop-sekolah {
            font-family: Tahoma, Arial, Helvetica, sans-serif;
            font-size: 16.5px;
            font-weight: bold;
            margin: 1px 0 0 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #000;
        }
        .kop-kabupaten {
            font-family: Tahoma, Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: bold;
            margin: 1px 0 3px 0;
            text-transform: uppercase;
            color: #000;
        }
        .kop-keahlian {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7px;
            font-weight: bold;
            margin: 0;
            line-height: 1.25;
            color: #000;
        }
        .kop-yayasan {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7px;
            font-weight: bold;
            margin: 2px 0 0 0;
            line-height: 1.25;
            color: #000;
        }
        .kop-alamat {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7.5px;
            font-style: italic;
            margin: 3px 0 0 0;
            line-height: 1.25;
            color: #000;
        }
        .kop-divider {
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin: 5px 0 14px 0;
        }

        .doc-title {
            text-align: center;
            margin-bottom: 14px;
        }
        .doc-title h2 {
            font-size: 13px;
            font-weight: bold;
            color: #1f3a6e;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-box {
            background-color: #f7f3ea;
            border: 1px solid #e2d9c6;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 10.5px;
            border-radius: 4px;
        }
        .meta-box table {
            margin: 0;
            width: 100%;
        }
        .meta-box td {
            border: none;
            padding: 3px 4px;
        }
        h3 {
            font-size: 12px;
            color: #1f3a6e;
            margin: 0 0 10px 0;
            text-transform: uppercase;
            font-weight: bold;
            border-left: 3px solid #c9951b;
            padding-left: 8px;
        }
        table.table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 10.5px;
        }
        table.table-data th, 
        table.table-data td {
            border: 1px solid #e2d9c6;
            padding: 7px 8px;
            text-align: left;
        }
        table.table-data th {
            background-color: #1f3a6e;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9.5px;
            letter-spacing: 0.5px;
        }
        table.table-data tr:nth-child(even) {
            background-color: #faf8f3;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer-ttd {
            margin-top: 36px;
            width: 100%;
            page-break-inside: avoid;
        }
        .footer-ttd td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
    </style>
</head>
<body>
@php
    $logoPath = public_path('images/kop-logo.png');
    if (!file_exists($logoPath)) {
        $logoPath = public_path('images/logo.png');
    }
    $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
@endphp

    <!-- NSS TABLE AT TOP RIGHT -->
    <table class="kop-nss-table">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: right;">
                <table class="nss-box-table" align="right">
                    <tr>
                        <td class="nss-label">NSS :</td>
                        <td class="nss-cell">4</td>
                        <td class="nss-cell">0</td>
                        <td class="nss-cell">2</td>
                        <td class="nss-cell">0</td>
                        <td class="nss-cell">3</td>
                        <td class="nss-cell">2</td>
                        <td class="nss-cell">9</td>
                        <td class="nss-cell">0</td>
                        <td class="nss-cell">1</td>
                        <td class="nss-cell">0</td>
                        <td class="nss-cell">4</td>
                        <td class="nss-cell">5</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- KOP SURAT UTAMA -->
    <table class="kop-main-table">
        <tr>
            <td class="kop-logo-cell">
                @if (!empty($logoBase64))
                    <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo SMK">
                @endif
            </td>
            <td class="kop-text-cell">
                <div class="kop-instansi">LEMBAGA PENDIDIKAN AL AMANAH</div>
                <div class="kop-sekolah">SMK ISLAM AL AMANAH SALEM</div>
                <div class="kop-kabupaten">KABUPATEN BREBES JAWA TENGAH</div>
                <div class="kop-keahlian">
                    NPSN : 20350723 &nbsp;&nbsp;&nbsp;&nbsp; Kompetensi Keahlian : 1. Teknik dan Bisnis Sepeda Motor (Terakreditasi B), &nbsp;2. Rekayasa Perangkat Lunak (Terakreditasi B)<br>
                    Konsentrasi Keahlian : 1. Teknik Sepeda Motor dan Teknik Ototronik, &nbsp;2. Rekayasa Perangkat Lunak dan Pengembangan Gim
                </div>
                <div class="kop-yayasan">
                    YAYASAN ISLAM MINNA L MASYRIQ - Akte Notaris : No 1 Tanggal 4 September 1992<br>
                    Diperbaharui : Akte Notaris No. 72 Tanggal 20 Agustus 2015 - SK. Menkumham No. AHU-0011614.AH.01.04 Tahun 2015
                </div>
                <div class="kop-alamat">
                    Jl. Kauman Barat Desa Bentar Kec. Salem Kab. Brebes KP. 52275, &nbsp;E-mail : <span style="color: #1f497d;">smkalamanah.salembrebes@gmail.com</span>
                </div>
            </td>
            <td class="kop-spacer-cell"></td>
        </tr>
    </table>

    <!-- GARIS PEMISAH KOP -->
    <div class="kop-divider"></div>

    <div class="doc-title">
        <h2>Laporan Arsip Presensi Guru</h2>
    </div>

    <div class="meta-box">
        <table>
            <tr>
                <td style="width: 20%; font-weight: bold;">Tahun Ajaran</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%; font-weight: bold; color: #1f3a6e;">{{ $arsip->nama_label }}</td>
                <td style="width: 18%; font-weight: bold;">Tanggal Arsip</td>
                <td style="width: 2%;">:</td>
                <td style="width: 20%;">{{ $arsip->created_at->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Periode Presensi</td>
                <td>:</td>
                <td>{{ $arsip->periode_teks }}</td>
                <td style="font-weight: bold;">Tanggal Unduh</td>
                <td>:</td>
                <td>{{ now()->translatedFormat('d/m/Y H:i') }} WIB</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Total Catatan</td>
                <td>:</td>
                <td colspan="4">
                    <strong>{{ number_format($arsip->total_presensi, 0, ',', '.') }} data</strong> 
                    (Hadir: {{ number_format($arsip->total_hadir, 0, ',', '.') }} | Terlambat: {{ number_format($arsip->total_terlambat, 0, ',', '.') }} | Izin: {{ number_format($arsip->total_izin, 0, ',', '.') }} | Sakit: {{ number_format($arsip->total_sakit, 0, ',', '.') }})
                </td>
            </tr>
            @if ($arsip->keterangan)
                <tr>
                    <td style="font-weight: bold;">Keterangan</td>
                    <td>:</td>
                    <td colspan="4" style="font-style: italic;">{{ $arsip->keterangan }}</td>
                </tr>
            @endif
        </table>
    </div>

    <h3>REKAPITULASI KEHADIRAN PER DEWAN GURU</h3>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 6%;" class="text-center">No</th>
                <th style="width: 36%;">Nama Guru</th>
                <th style="width: 22%;">NIP / Bidang Studi</th>
                <th style="width: 12%;" class="text-center">Hadir</th>
                <th style="width: 12%;" class="text-center">Izin</th>
                <th style="width: 12%;" class="text-center">Sakit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekapGuru as $index => $rg)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><strong>{{ $rg->nama_guru }}</strong></td>
                    <td>
                        {{ $rg->mata_pelajaran ? 'Guru ' . $rg->mata_pelajaran : ($rg->nip_guru ? 'NIP: ' . $rg->nip_guru : '-') }}
                    </td>
                    <td class="text-center">{{ $rg->hadir }} kali</td>
                    <td class="text-center">{{ $rg->izin }} kali</td>
                    <td class="text-center">{{ $rg->sakit }} kali</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: #94a3b8; padding: 18px;">
                        Tidak ada data guru pada arsip ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $kepalaSekolah = $kepsek ?? \App\Models\User::where('role', 'kepala_sekolah')->first();
        $namaKepsek = $kepalaSekolah?->nama ?? '( .................................................. )';
    @endphp

    <table class="footer-ttd">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <p style="margin: 0;">Salem, {{ now()->translatedFormat('d F Y') }}</p>
                <p style="margin: 4px 0 65px 0; font-weight: bold;">Kepala SMK Islam Al-Amanah,</p>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $namaKepsek }}</p>
                <p style="margin: 3px 0 0 0; color: #64748b; font-size: 9.5px;">NIP/NUPTK: -</p>
            </td>
        </tr>
    </table>
</body>
</html>

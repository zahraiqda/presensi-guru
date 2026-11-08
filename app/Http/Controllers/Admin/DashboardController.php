<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Pengaturan;
use App\Models\Presensi;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hariIni = today()->toDateString();
        $pengaturan = Pengaturan::aktif();

        $totalGuru = Guru::count();

        $hadirHariIni = Presensi::whereDate('tanggal', $hariIni)->where('status', 'masuk')->count();
        $sakitHariIni = Presensi::whereDate('tanggal', $hariIni)->where('status', 'sakit')->count();
        $izinHariIni = Presensi::whereDate('tanggal', $hariIni)->where('status', 'izin')->count();

        $presensiHariIni = Presensi::with('guru')
            ->whereDate('tanggal', $hariIni)
            ->orderByDesc('waktu')
            ->take(10)
            ->get();

        $ringkasanGuru = Guru::with(['presensi' => function ($query) use ($hariIni) {
            $query->whereDate('tanggal', $hariIni);
        }])->get()->map(function (Guru $guru) {
            $masuk = $guru->presensi->firstWhere('status', 'masuk');
            $pulang = $guru->presensi->firstWhere('status', 'pulang');
            $sakit = $guru->presensi->firstWhere('status', 'sakit');
            $izin = $guru->presensi->firstWhere('status', 'izin');

            $status = match (true) {
                (bool) $sakit => 'Sakit',
                (bool) $izin => 'Izin',
                (bool) $masuk => 'Hadir',
                default => 'Belum Presensi',
            };

            return [
                'nama' => $guru->nama,
                'nik' => $guru->nik,
                'mata_pelajaran' => $guru->mata_pelajaran,
                'label_mapel' => $guru->label_mapel,
                'jam_masuk' => $masuk?->waktu,
                'jam_pulang' => $pulang?->waktu,
                'status' => $status,
                'keterangan' => $sakit?->keterangan ?? $izin?->keterangan,
                'bukti_sakit' => $sakit?->bukti_sakit,
                'bukti_izin' => $izin?->bukti_izin,
                'approval_status' => $izin?->approval_status,
                'catatan_kepsek' => $izin?->catatan_kepsek,
                'token_izin' => $izin?->token_izin,
            ];
        });

        return view('admin.dashboard', compact(
            'totalGuru', 'hadirHariIni', 'sakitHariIni', 'izinHariIni', 'presensiHariIni', 'ringkasanGuru', 'pengaturan'
        ));
    }
}
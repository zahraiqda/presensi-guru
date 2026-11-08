<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $guru = Auth::user()->guru;
        $hariIni = today()->toDateString();
        $pengaturan = Pengaturan::aktif();

        $jamMasuk = null;
        $jamPulang = null;
        $riwayat = collect();

        if ($guru) {
            $masuk = Presensi::where('guru_id', $guru->id)->whereDate('tanggal', $hariIni)->where('status', 'masuk')->first();
            $pulang = Presensi::where('guru_id', $guru->id)->whereDate('tanggal', $hariIni)->where('status', 'pulang')->first();

            $jamMasuk = $masuk?->waktu;
            $jamPulang = $pulang?->waktu;

            $riwayat = $guru->riwayatTerakhir(5)->get();
        }

        return view('guru.dashboard', compact('guru', 'jamMasuk', 'jamPulang', 'riwayat', 'pengaturan'));
    }
}
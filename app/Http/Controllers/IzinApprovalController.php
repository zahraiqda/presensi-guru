<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\Presensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IzinApprovalController extends Controller
{
    /**
     * Tampilkan halaman persetujuan izin untuk Kepala Sekolah via link WhatsApp.
     */
    public function show(string $token): View
    {
        $presensi = Presensi::with('guru')
            ->where('token_izin', $token)
            ->where('status', 'izin')
            ->firstOrFail();

        $pengaturan = Pengaturan::aktif();

        return view('izin.approval', compact('presensi', 'pengaturan'));
    }

    /**
     * Proses persetujuan atau penolakan izin oleh Kepala Sekolah.
     */
    public function proses(Request $request, string $token): RedirectResponse
    {
        $presensi = Presensi::where('token_izin', $token)
            ->where('status', 'izin')
            ->firstOrFail();

        $request->validate([
            'aksi' => ['required', 'in:setujui,tolak'],
            'catatan_kepsek' => ['nullable', 'string', 'max:500'],
        ]);

        $statusApproval = $request->aksi === 'setujui' ? 'disetujui' : 'ditolak';

        $presensi->update([
            'approval_status' => $statusApproval,
            'catatan_kepsek' => $request->catatan_kepsek,
            'approved_at' => now(),
        ]);

        $pesan = $statusApproval === 'disetujui'
            ? 'Izin guru berhasil DISETUJUI.'
            : 'Izin guru telah DITOLAK.';

        return back()->with('status_proses', $pesan);
    }
}

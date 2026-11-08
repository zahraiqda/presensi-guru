<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\ArsipTahunAjaran;
use App\Models\Pengaturan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArsipController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->query('q');

        $query = ArsipTahunAjaran::with('pembuat')
            ->withCount('presensi')
            ->latest();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('tahun_ajaran', 'like', "%{$q}%")
                    ->orWhere('nama_label', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%");
            });
        }

        $arsipList = $query->paginate(12)->withQueryString();

        $totalArsip = ArsipTahunAjaran::count();
        $totalPresensiDiarsipkan = ArsipTahunAjaran::sum('total_presensi');

        return view('admin.arsip.index', [
            'arsipList' => $arsipList,
            'totalArsip' => $totalArsip,
            'totalPresensiDiarsipkan' => $totalPresensiDiarsipkan,
            'q' => $q,
            'isAdmin' => false,
        ]);
    }

    public function show(Request $request, ArsipTahunAjaran $arsip): View
    {
        $q = $request->query('q');
        $status = $request->query('status');
        $mulai = $request->query('mulai_tanggal');
        $sampai = $request->query('sampai_tanggal');

        $query = $arsip->presensi()->with('guru')->latest('tanggal')->latest('waktu');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_guru', 'like', "%{$q}%")
                    ->orWhere('nip_guru', 'like', "%{$q}%")
                    ->orWhere('mata_pelajaran', 'like', "%{$q}%");
            });
        }

        if ($status && in_array($status, ['masuk', 'pulang', 'sakit', 'izin'])) {
            $query->where('status', $status);
        }

        if ($mulai) {
            $query->whereDate('tanggal', '>=', $mulai);
        }

        if ($sampai) {
            $query->whereDate('tanggal', '<=', $sampai);
        }

        $presensiList = $query->paginate(25)->withQueryString();

        $rekapGuru = $arsip->presensi()
            ->select('nama_guru', 'nip_guru', 'mata_pelajaran')
            ->selectRaw("COUNT(*) as total_catatan")
            ->selectRaw("SUM(CASE WHEN status IN ('masuk', 'pulang') THEN 1 ELSE 0 END) as hadir")
            ->selectRaw("SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit")
            ->selectRaw("SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin")
            ->groupBy('nama_guru', 'nip_guru', 'mata_pelajaran')
            ->orderBy('nama_guru')
            ->get();

        return view('admin.arsip.show', [
            'arsip' => $arsip,
            'presensiList' => $presensiList,
            'rekapGuru' => $rekapGuru,
            'q' => $q,
            'status' => $status,
            'mulai' => $mulai,
            'sampai' => $sampai,
            'isAdmin' => false,
        ]);
    }

    public function downloadPdf(ArsipTahunAjaran $arsip)
    {
        $presensiList = $arsip->presensi()
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu', 'asc')
            ->get();

        $rekapGuru = $arsip->presensi()
            ->select('nama_guru', 'nip_guru', 'mata_pelajaran')
            ->selectRaw("COUNT(*) as total_catatan")
            ->selectRaw("SUM(CASE WHEN status IN ('masuk', 'pulang') THEN 1 ELSE 0 END) as hadir")
            ->selectRaw("SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit")
            ->selectRaw("SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin")
            ->groupBy('nama_guru', 'nip_guru', 'mata_pelajaran')
            ->orderBy('nama_guru')
            ->get();

        $kepsek = User::where('role', 'kepala_sekolah')->first();
        $pengaturan = Pengaturan::aktif();

        $pdf = Pdf::loadView('admin.arsip.pdf', [
            'arsip' => $arsip,
            'presensiList' => $presensiList,
            'rekapGuru' => $rekapGuru,
            'kepsek' => $kepsek,
            'pengaturan' => $pengaturan,
        ])->setPaper('a4', 'portrait');

        $namaFile = 'arsip-presensi-' . Str::slug($arsip->nama_label) . '.pdf';
        return $pdf->download($namaFile);
    }
}

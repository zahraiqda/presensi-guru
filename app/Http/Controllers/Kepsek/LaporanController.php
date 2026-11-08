<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends AdminLaporanController
{
    /**
     * Rekap Laporan versi Kepala Sekolah (read-only, tanpa pengaturan).
     * downloadPdf() diwarisi dari controller Admin.
     */
    public function index(Request $request): View
    {
        $mulai = $request->query('mulai_tanggal', now()->startOfMonth()->toDateString());
        $sampai = $request->query('sampai_tanggal', now()->toDateString());

        $data = $this->hitungAnalisis($mulai, $sampai, Pengaturan::aktif());

        return view('kepsek.laporan.index', array_merge($data, [
            'mulai' => $mulai,
            'sampai' => $sampai,
        ]));
    }
}
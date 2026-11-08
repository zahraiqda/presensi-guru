<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LogRiwayatController extends Controller
{
    public function index(Request $request): View
    {
        $tanggal = $request->query('tanggal');
        $status = $request->query('status');
        $guruId = $request->query('guru_id');

        $query = Presensi::with('guru')
            ->when($tanggal, fn ($q) => $q->whereDate('tanggal', $tanggal))
            ->when($guruId, fn ($q) => $q->where('guru_id', $guruId))
            ->orderByDesc('tanggal')
            ->orderBy('waktu');

        $rawLog = $query->get();

        $logHarian = $rawLog->groupBy(function ($item) {
            return $item->tanggal->toDateString() . '_' . $item->guru_id;
        })->map(function ($items) {
            $pertama = $items->first();
            $guru = $pertama->guru;
            $tanggal = $pertama->tanggal;

            $masuk = $items->firstWhere('status', 'masuk');
            $pulang = $items->firstWhere('status', 'pulang');
            $sakit = $items->firstWhere('status', 'sakit');
            $izin = $items->firstWhere('status', 'izin');

            $statusText = match (true) {
                (bool) $sakit => 'Sakit',
                (bool) $izin => 'Izin',
                (bool) $masuk => 'Hadir',
                (bool) $pulang => 'Hadir',
                default => 'Hadir',
            };

            return (object) [
                'tanggal' => $tanggal,
                'guru' => $guru,
                'nama' => $guru?->nama ?? '-',
                'jam_masuk' => $masuk ? Carbon::parse($masuk->waktu)->format('H:i') : null,
                'jam_pulang' => $pulang ? Carbon::parse($pulang->waktu)->format('H:i') : null,
                'lat_masuk' => $masuk?->latitude,
                'long_masuk' => $masuk?->longitude,
                'lat_pulang' => $pulang?->latitude,
                'long_pulang' => $pulang?->longitude,
                'status' => $statusText,
                'keterangan' => $sakit?->keterangan ?? $izin?->keterangan ?? null,
                'bukti_sakit' => $sakit?->bukti_sakit,
                'bukti_izin' => $izin?->bukti_izin,
                'approval_status' => $izin?->approval_status,
                'catatan_kepsek' => $izin?->catatan_kepsek,
                'token_izin' => $izin?->token_izin,
                'sort_key' => $tanggal->toDateString() . '_' . ($masuk?->waktu ?? $pulang?->waktu ?? '00:00:00'),
            ];
        })->values();

        if ($status) {
            $logHarian = $logHarian->filter(function ($item) use ($status) {
                if (strtolower($status) === 'masuk') {
                    return ! empty($item->jam_masuk);
                }
                if (strtolower($status) === 'pulang') {
                    return ! empty($item->jam_pulang);
                }
                return strtolower($item->status) === strtolower($status);
            })->values();
        }

        $logHarian = $logHarian->sortByDesc('sort_key')->values();

        $page = (int) $request->query('page', 1);
        $perPage = 20;
        $pagedData = new LengthAwarePaginator(
            $logHarian->forPage($page, $perPage)->values(),
            $logHarian->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $daftarGuru = Guru::orderBy('nama')->get(['id', 'nama']);

        return view('kepsek.log.index', [
            'log' => $pagedData,
            'daftarGuru' => $daftarGuru,
            'tanggal' => $tanggal,
            'status' => $status,
            'guruId' => $guruId,
        ]);
    }
}
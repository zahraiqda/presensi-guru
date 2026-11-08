<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArsipPresensi;
use App\Models\ArsipTahunAjaran;
use App\Models\Guru;
use App\Models\Pengaturan;
use App\Models\Presensi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $mulai = $request->query('mulai_tanggal', now()->startOfMonth()->toDateString());
        $sampai = $request->query('sampai_tanggal', now()->toDateString());

        $pengaturan = Pengaturan::aktif();

        $data = $this->hitungAnalisis($mulai, $sampai, $pengaturan);

        // Saran Tahun Ajaran & Semester berdasarkan bulan saat ini
        $tahunSekarang = (int) date('Y');
        $bulanSekarang = (int) date('n');
        if ($bulanSekarang >= 7) {
            $saranTahun = $tahunSekarang . '/' . ($tahunSekarang + 1);
            $saranSemester = 'ganjil';
        } else {
            $saranTahun = ($tahunSekarang - 1) . '/' . $tahunSekarang;
            $saranSemester = 'genap';
        }

        $totalPresensiAktif = Presensi::count();

        return view('admin.laporan.index', array_merge($data, [
            'mulai' => $mulai,
            'sampai' => $sampai,
            'pengaturan' => $pengaturan,
            'saranTahun' => $saranTahun,
            'saranSemester' => $saranSemester,
            'totalPresensiAktif' => $totalPresensiAktif,
        ]));
    }

    public function updateJamKerja(Request $request): RedirectResponse
    {
        $timeFields = ['jam_masuk_mulai', 'jam_masuk_toleransi', 'jam_masuk_selesai', 'jam_pulang_minimal', 'jam_pulang_selesai'];
        $normalized = [];
        foreach ($timeFields as $field) {
            $val = $request->input($field);
            if ($val) {
                $normalized[$field] = substr($val, 0, 5);
            }
        }
        $request->merge($normalized);

        $validated = $request->validate([
            'jam_masuk_mulai' => ['required', 'date_format:H:i'],
            'jam_masuk_toleransi' => ['required', 'date_format:H:i'],
            'jam_masuk_selesai' => ['required', 'date_format:H:i'],
            'jam_pulang_minimal' => ['required', 'date_format:H:i'],
            'jam_pulang_selesai' => ['required', 'date_format:H:i'],
            'latitude_sekolah' => ['required', 'numeric', 'between:-90,90'],
            'longitude_sekolah' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'integer', 'min:10', 'max:5000'],
            'no_wa_kepsek' => ['nullable', 'string', 'max:25'],
        ]);

        Pengaturan::aktif()->update($validated);

        return back()->with('status', 'Standar jam kerja, lokasi, & nomor WA Kepala Sekolah berhasil diperbarui.');
    }

    public function kosongkanPresensi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:50'],
            'semester' => ['required', 'in:semua,ganjil,genap'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ], [
            'tahun_ajaran.required' => 'Nama Tahun Ajaran wajib diisi untuk pengarsipan.',
        ]);

        $totalPresensi = Presensi::count();
        if ($totalPresensi === 0) {
            return back()->with('error', 'Tidak ada riwayat presensi aktif yang dapat diarsipkan.');
        }

        $namaLabel = match ($validated['semester']) {
            'ganjil' => $validated['tahun_ajaran'] . ' - Semester Ganjil',
            'genap' => $validated['tahun_ajaran'] . ' - Semester Genap',
            default => $validated['tahun_ajaran'] . ' (1 Tahun Penuh)',
        };

        $pengaturan = Pengaturan::aktif();
        $jamMasukSelesai = $pengaturan->jam_masuk_selesai ? Carbon::parse($pengaturan->jam_masuk_selesai)->format('H:i:s') : '07:15:00';

        $arsip = DB::transaction(function () use ($validated, $namaLabel, $jamMasukSelesai, $totalPresensi) {
            $minTanggal = Presensi::min('tanggal');
            $maxTanggal = Presensi::max('tanggal');

            $totalHadir = Presensi::whereIn('status', ['masuk', 'pulang'])->count();
            $totalTerlambat = Presensi::where('status', 'masuk')
                ->whereTime('waktu', '>', $jamMasukSelesai)
                ->count();
            $totalSakit = Presensi::where('status', 'sakit')->count();
            $totalIzin = Presensi::where('status', 'izin')->count();

            $arsipBaru = ArsipTahunAjaran::create([
                'tahun_ajaran' => $validated['tahun_ajaran'],
                'semester' => $validated['semester'],
                'nama_label' => $namaLabel,
                'keterangan' => $validated['keterangan'] ?? null,
                'tanggal_mulai' => $minTanggal,
                'tanggal_selesai' => $maxTanggal,
                'total_presensi' => $totalPresensi,
                'total_hadir' => $totalHadir,
                'total_terlambat' => $totalTerlambat,
                'total_sakit' => $totalSakit,
                'total_izin' => $totalIzin,
                'diarsipkan_oleh' => auth()->id(),
            ]);

            Presensi::with('guru')->chunk(200, function ($records) use ($arsipBaru) {
                $batch = [];
                $now = now();
                foreach ($records as $item) {
                    $batch[] = [
                        'arsip_tahun_ajaran_id' => $arsipBaru->id,
                        'guru_id' => $item->guru_id,
                        'nama_guru' => $item->guru?->nama ?? 'Guru (Data Dihapus)',
                        'nip_guru' => $item->guru?->nip,
                        'mata_pelajaran' => $item->guru?->mata_pelajaran,
                        'tanggal' => $item->tanggal ? $item->tanggal->toDateString() : null,
                        'waktu' => $item->waktu,
                        'status' => $item->status,
                        'latitude' => $item->latitude,
                        'longitude' => $item->longitude,
                        'keterangan' => $item->keterangan,
                        'bukti_sakit' => $item->bukti_sakit,
                        'token_izin' => $item->token_izin,
                        'approval_status' => $item->approval_status,
                        'approved_at' => $item->approved_at,
                        'bukti_izin' => $item->bukti_izin,
                        'catatan_kepsek' => $item->catatan_kepsek,
                        'created_at' => $item->created_at ?? $now,
                        'updated_at' => $item->updated_at ?? $now,
                    ];
                }
                ArsipPresensi::insert($batch);
            });

            // Kosongkan presensi aktif
            Presensi::query()->delete();

            return $arsipBaru;
        });

        return redirect()->route('admin.arsip.show', $arsip->id)
            ->with('status', 'Semua riwayat presensi berhasil diarsipkan ke "' . $namaLabel . '". Presensi aktif kini telah dimulai dari 0.');
    }

    public function downloadPdf(Request $request)
    {
        $mulai = $request->query('mulai_tanggal', now()->startOfMonth()->toDateString());
        $sampai = $request->query('sampai_tanggal', now()->toDateString());

        $pengaturan = Pengaturan::aktif();
        $data = $this->hitungAnalisis($mulai, $sampai, $pengaturan);

        $kepsek = User::where('role', 'kepala_sekolah')->first();

        $pdf = Pdf::loadView('admin.laporan.pdf', array_merge($data, [
            'mulai' => $mulai,
            'sampai' => $sampai,
            'kepsek' => $kepsek,
        ]));

        return $pdf->download('rekap-presensi-' . $mulai . '-sd-' . $sampai . '.pdf');
    }

    protected function hitungAnalisis(string $mulai, string $sampai, Pengaturan $pengaturan): array
    {
        $guruList = Guru::with(['presensi' => function ($query) use ($mulai, $sampai) {
            $query->periode($mulai, $sampai);
        }])->get();

        $palingCepat = collect();
        $terlambat = collect();
        $durasiTerpendek = collect();
        $lupaPulang = collect();
        $seringSakit = collect();
        $seringIzin = collect();
        $rekap = collect();

        $toleransi = Carbon::parse($pengaturan->jam_masuk_toleransi)->format('H:i:s');

        foreach ($guruList as $guru) {
            $presensiPerTanggal = $guru->presensi->groupBy(fn ($p) => $p->tanggal->toDateString());

            $jamMasukTerawal = null;
            $jumlahTerlambat = 0;
            $jumlahLupaPulang = 0;
            $durasiHarian = [];
            $hadir = 0;
            $sakit = $guru->presensi->where('status', 'sakit')->count();
            $izin = $guru->presensi->where('status', 'izin')->filter(fn ($p) => $p->approval_status !== 'ditolak')->count();

            foreach ($presensiPerTanggal as $tanggal => $items) {
                $masuk = $items->firstWhere('status', 'masuk');
                $pulang = $items->firstWhere('status', 'pulang');

                if ($masuk) {
                    $hadir++;

                    if (is_null($jamMasukTerawal) || $masuk->waktu < $jamMasukTerawal) {
                        $jamMasukTerawal = $masuk->waktu;
                    }

                    if ($masuk->waktu > $toleransi) {
                        $jumlahTerlambat++;
                    }

                    if ($pulang) {
                        $menit = Carbon::parse($pulang->waktu)->diffInMinutes(Carbon::parse($masuk->waktu));
                        $durasiHarian[] = $menit;
                    } else {
                        $jumlahLupaPulang++;
                    }
                }
            }

            $rataDurasi = count($durasiHarian) > 0 ? array_sum($durasiHarian) / count($durasiHarian) : null;

            $rekap->push(['nama' => $guru->nama, 'hadir' => $hadir, 'sakit' => $sakit, 'izin' => $izin]);

            if ($jamMasukTerawal) {
                $palingCepat->push(['nama' => $guru->nama, 'jam' => $jamMasukTerawal]);
            }

            if ($jumlahTerlambat > 0) {
                $terlambat->push(['nama' => $guru->nama, 'jumlah' => $jumlahTerlambat]);
            }

            if (! is_null($rataDurasi)) {
                $durasiTerpendek->push(['nama' => $guru->nama, 'rata' => round($rataDurasi)]);
            }

            if ($jumlahLupaPulang > 0) {
                $lupaPulang->push(['nama' => $guru->nama, 'jumlah' => $jumlahLupaPulang]);
            }

            if ($sakit > 0) {
                $seringSakit->push(['nama' => $guru->nama, 'jumlah' => $sakit]);
            }

            if ($izin > 0) {
                $seringIzin->push(['nama' => $guru->nama, 'jumlah' => $izin]);
            }
        }

        $rawLog = Presensi::with('guru')
            ->periode($mulai, $sampai)
            ->orderByDesc('tanggal')
            ->orderBy('waktu')
            ->get();

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

            $status = match (true) {
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
                'status' => $status,
                'keterangan' => $sakit?->keterangan ?? $izin?->keterangan ?? null,
                'bukti_sakit' => $sakit?->bukti_sakit,
                'bukti_izin' => $izin?->bukti_izin,
                'approval_status' => $izin?->approval_status,
                'catatan_kepsek' => $izin?->catatan_kepsek,
                'token_izin' => $izin?->token_izin,
                'sort_key' => $tanggal->toDateString() . '_' . ($masuk?->waktu ?? $pulang?->waktu ?? '00:00:00'),
            ];
        })->sortByDesc('sort_key')->values();

        return [
            'palingCepat' => $palingCepat->sortBy('jam')->take(3)->values(),
            'terlambat' => $terlambat->sortByDesc('jumlah')->take(3)->values(),
            'durasiTerpendek' => $durasiTerpendek->sortBy('rata')->take(3)->values(),
            'lupaPulang' => $lupaPulang->sortByDesc('jumlah')->take(3)->values(),
            'seringSakit' => $seringSakit->sortByDesc('jumlah')->take(3)->values(),
            'seringIzin' => $seringIzin->sortByDesc('jumlah')->take(3)->values(),
            'rekap' => $rekap->sortByDesc('hadir')->values(),
            'logRiwayat' => $logHarian,
            'logHarian' => $logHarian,
        ];
    }
}
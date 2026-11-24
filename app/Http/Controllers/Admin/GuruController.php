<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class GuruController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->query('q');

        $guru = Guru::when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('nik', 'like', "%{$keyword}%")
                      ->orWhere('mata_pelajaran', 'like', "%{$keyword}%");
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.guru.index', compact('guru', 'keyword'));
    }

    public function create(): View
    {
        return view('admin.guru.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:16', 'max:20', 'unique:guru,nik'],
            'nama' => ['required', 'string', 'max:100'],
            'mata_pelajaran' => ['nullable', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.regex' => 'NIK harus berupa digit angka tanpa huruf atau simbol.',
            'nik.min' => 'NIK minimal harus 16 digit (sesuai standar jumlah digit NIK).',
            'nik.max' => 'NIK maksimal 20 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar pada sistem.',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'nama' => $validated['nama'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role' => 'guru',
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nik' => $validated['nik'],
                'nama' => $validated['nama'],
                'mata_pelajaran' => $validated['mata_pelajaran'] ?? null,
            ]);
        });

        return redirect()->route('admin.guru.index')->with('status', 'Data guru & staf berhasil ditambahkan.');
    }

    public function edit(Guru $guru): View
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:16', 'max:20', 'unique:guru,nik,' . $guru->id],
            'nama' => ['required', 'string', 'max:100'],
            'mata_pelajaran' => ['nullable', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $guru->user_id],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.regex' => 'NIK harus berupa digit angka tanpa huruf atau simbol.',
            'nik.min' => 'NIK minimal harus 16 digit (sesuai standar jumlah digit NIK).',
            'nik.max' => 'NIK maksimal 20 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar pada sistem.',
        ]);

        DB::transaction(function () use ($validated, $guru) {
            $guru->update([
                'nik' => $validated['nik'],
                'nama' => $validated['nama'],
                'mata_pelajaran' => $validated['mata_pelajaran'] ?? null,
            ]);

            $dataUser = ['nama' => $validated['nama'], 'username' => $validated['username']];

            if (! empty($validated['password'])) {
                $dataUser['password'] = Hash::make($validated['password']);
            }

            $guru->user->update($dataUser);
        });

        return redirect()->route('admin.guru.index')->with('status', 'Data guru & staf berhasil diperbarui.');
    }

    public function destroy(Guru $guru): RedirectResponse
    {
        DB::transaction(function () use ($guru) {
            $user = $guru->user;
            $guru->delete();
            $user?->delete();
        });

        return redirect()->route('admin.guru.index')->with('status', 'Data guru & staf berhasil dihapus.');
    }

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_guru_staf.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            // Menulis UTF-8 BOM agar rapi saat dibuka di Microsoft Excel atau WPS Office
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            // Baris header
            fputcsv($handle, ['nik', 'nama', 'jabatan_atau_mapel']);
            // Contoh baris data
            fputcsv($handle, ['3329010101900001', 'Aan Nur Hasanah, S.Pd., Gr.', 'Guru Mapel B. Indonesia']);
            fputcsv($handle, ['3329010202910002', 'Apif Maulana Yusuf, S.Pt., Gr.', 'Operator Sekolah']);
            fputcsv($handle, ['3329010303920003', 'Candika Yulian', 'Staf']);
            fclose($handle);
        }, 200, $headers);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate(['file_csv' => ['required', 'file', 'mimes:csv,txt']]);

        $path = $request->file('file_csv')->getRealPath();
        $handle = fopen($path, 'r');

        // Deteksi delimiter (koma atau titik koma) agar mendukung WPS/Excel versi Indonesia
        $sampleLine = fgets($handle);
        $delimiter = (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) ? ';' : ',';
        rewind($handle);

        $baris = 0;
        $berhasil = 0;
        $barisKosong = 0;
        $nikTidakSesuai = 0;
        $namaKosong = 0;
        $duplikat = 0;

        while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $baris++;

            // Lewati baris header jika baris pertama atau mengandung kata kunci header
            if ($baris === 1) {
                $col0 = strtolower(trim($data[0] ?? ''));
                $col1 = strtolower(trim($data[1] ?? ''));
                if ($col0 === 'nik' || str_contains($col0, 'nama') || str_contains($col1, 'nama')) {
                    continue;
                }
            }

            $col0Raw = trim($data[0] ?? '');
            $col1Raw = trim($data[1] ?? '');
            $col2Raw = trim($data[2] ?? '');

            // Abaikan jika seluruh kolom dalam baris ini kosong (misal sisa baris kosong saat export dari WPS/Excel)
            if ($col0Raw === '' && $col1Raw === '' && $col2Raw === '') {
                $barisKosong++;
                continue;
            }

            $nik = preg_replace('/[^0-9]/', '', $col0Raw);
            $nama = $col1Raw;
            $mataPelajaran = $col2Raw !== '' ? $col2Raw : null;

            // NIK wajib berupa angka tepat 16 digit sesuai standar NIK
            if (strlen($nik) !== 16) {
                $nikTidakSesuai++;
                continue;
            }

            // Nama lengkap wajib diisi
            if ($nama === '') {
                $namaKosong++;
                continue;
            }

            // Cek apakah NIK atau username sudah terdaftar
            if (Guru::where('nik', $nik)->exists() || User::where('username', $nik)->exists()) {
                $duplikat++;
                continue;
            }

            DB::transaction(function () use ($nik, $nama, $mataPelajaran) {
                $user = User::create([
                    'nama' => $nama,
                    'username' => $nik,
                    'password' => Hash::make($nik),
                    'role' => 'guru',
                ]);

                Guru::create([
                    'user_id' => $user->id,
                    'nik' => $nik,
                    'nama' => $nama,
                    'mata_pelajaran' => $mataPelajaran,
                ]);
            });

            $berhasil++;
        }

        fclose($handle);

        // Jika tidak ada satupun data yang berhasil diimport
        if ($berhasil === 0) {
            if ($nikTidakSesuai > 0) {
                return redirect()->route('admin.guru.index')
                    ->with('error', "Import gagal: Format file atau kolom tidak sesuai petunjuk! Ditemukan {$nikTidakSesuai} baris di mana Kolom 1 bukan 16 digit NIK. Pastikan Kolom 1 berisi NIK (wajib 16 digit angka), Kolom 2 Nama Lengkap, dan Kolom 3 Jabatan/Mapel.");
            }

            if ($duplikat > 0) {
                return redirect()->route('admin.guru.index')
                    ->with('warning', "Tidak ada data baru yang ditambahkan: Semua data ({$duplikat} baris) sudah terdaftar di sistem.");
            }

            return redirect()->route('admin.guru.index')
                ->with('error', "Import gagal: File CSV kosong atau tidak ditemukan data guru & staf yang valid.");
        }

        // Jika ada data yang berhasil diimport
        $pesan = "Import selesai: {$berhasil} guru & staf berhasil ditambahkan.";
        $keteranganDilewati = [];
        if ($nikTidakSesuai > 0) {
            $keteranganDilewati[] = "{$nikTidakSesuai} baris dilewati karena NIK tidak 16 digit";
        }
        if ($duplikat > 0) {
            $keteranganDilewati[] = "{$duplikat} baris dilewati karena NIK sudah terdaftar";
        }
        if ($namaKosong > 0) {
            $keteranganDilewati[] = "{$namaKosong} baris dilewati karena Nama kosong";
        }

        if (!empty($keteranganDilewati)) {
            $pesan .= ' (' . implode(', ', $keteranganDilewati) . ').';
        }

        return redirect()->route('admin.guru.index')->with('status', $pesan);
    }
}
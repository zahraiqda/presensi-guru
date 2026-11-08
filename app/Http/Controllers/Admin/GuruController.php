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

        return redirect()->route('admin.guru.index')->with('status', 'Data guru berhasil ditambahkan.');
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

        return redirect()->route('admin.guru.index')->with('status', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru): RedirectResponse
    {
        DB::transaction(function () use ($guru) {
            $user = $guru->user;
            $guru->delete();
            $user?->delete();
        });

        return redirect()->route('admin.guru.index')->with('status', 'Data guru berhasil dihapus.');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate(['file_csv' => ['required', 'file', 'mimes:csv,txt']]);

        $path = $request->file('file_csv')->getRealPath();
        $handle = fopen($path, 'r');

        $baris = 0;
        $berhasil = 0;
        $dilewati = 0;

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $baris++;

            if ($baris === 1) {
                continue;
            }

            $nik = trim($data[0] ?? '');
            $nama = trim($data[1] ?? '');
            $mataPelajaran = isset($data[2]) && trim($data[2]) !== '' ? trim($data[2]) : null;

            if ($nik === '' || $nama === '' || Guru::where('nik', $nik)->exists() || User::where('username', $nik)->exists()) {
                $dilewati++;
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

        return redirect()->route('admin.guru.index')
            ->with('status', "Import selesai: {$berhasil} guru ditambahkan, {$dilewati} baris dilewati (duplikat/kosong).");
    }
}
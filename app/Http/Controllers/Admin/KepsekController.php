<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KepsekController extends Controller
{
    /**
     * Tampilkan halaman manajemen akun Kepala Sekolah.
     */
    public function index(): View
    {
        $kepsekList = User::where('role', 'kepala_sekolah')->latest()->get();
        $pengaturan = Pengaturan::aktif();

        return view('admin.kepsek.index', compact('kepsekList', 'pengaturan'));
    }

    /**
     * Reset password akun Kepala Sekolah oleh Admin.
     */
    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        if (! $user->isKepalaSekolah()) {
            abort(403, 'Aksi ini hanya diperbolehkan untuk akun Kepala Sekolah.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Password Kepala Sekolah (' . $user->nama . ') berhasil direset menjadi: ' . $validated['password']);
    }

    /**
     * Perbarui data nama, username, dan nomor WA Kepala Sekolah.
     */
    public function updateProfile(Request $request, User $user): RedirectResponse
    {
        if (! $user->isKepalaSekolah()) {
            abort(403, 'Aksi ini hanya diperbolehkan untuk akun Kepala Sekolah.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'no_wa_kepsek' => ['nullable', 'string', 'max:25'],
        ], [
            'nama.required' => 'Nama Kepala Sekolah wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username tersebut sudah digunakan oleh akun lain.',
        ]);

        $user->update([
            'nama' => $validated['nama'],
            'username' => $validated['username'],
        ]);

        Pengaturan::aktif()->update([
            'no_wa_kepsek' => $validated['no_wa_kepsek'] ?? null,
        ]);

        return back()->with('status', 'Data profil & Nomor WhatsApp Kepala Sekolah (' . $user->nama . ') berhasil diperbarui.');
    }
}

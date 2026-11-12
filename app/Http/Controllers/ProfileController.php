<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $data = ['user' => $user];

        // Tampilkan email reset password untuk admin
        if ($user->role === 'admin') {
            $adminEmail = $user->email ?: env('ADMIN_RESET_EMAIL', 'admin@alamanah.sch.id');

            // Sinkronkan ke database jika kolom email user di DB masih kosong
            if (empty($user->email) && !empty($adminEmail)) {
                $user->update(['email' => $adminEmail]);
            }

            $data['adminResetEmail'] = $adminEmail;
        }

        return view('profile.edit', $data);
    }

    public function updateEmail(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(403, 'Aksi ini hanya diperbolehkan untuk Administrator.');
        }

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
        ]);

        // 1. Simpan email baru ke database
        $user->update([
            'email' => $validated['email'],
        ]);

        // 2. Sinkronkan secara otomatis ke file .env agar sinkron tanpa perlu buka kode
        $this->syncEnvAdminEmail($validated['email']);

        return back()->with('status_email', 'Email admin berhasil diperbarui menjadi: ' . $validated['email']);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Password berhasil diubah.');
    }

    /**
     * Memperbarui variabel ADMIN_RESET_EMAIL di file .env secara otomatis.
     */
    private function syncEnvAdminEmail(string $newEmail): void
    {
        $envPath = base_path('.env');
        if (file_exists($envPath) && is_writable($envPath)) {
            $envContent = file_get_contents($envPath);
            if (preg_match('/^ADMIN_RESET_EMAIL=.*/m', $envContent)) {
                $envContent = preg_replace('/^ADMIN_RESET_EMAIL=.*/m', 'ADMIN_RESET_EMAIL=' . $newEmail, $envContent);
            } else {
                $envContent .= "\nADMIN_RESET_EMAIL=" . $newEmail;
            }
            @file_put_contents($envPath, $envContent);
        }
    }
}
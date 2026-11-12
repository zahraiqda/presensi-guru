<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['username' => ['required', 'string']]);

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return back()->withErrors(['username' => 'Username tidak ditemukan.']);
        }

        if ($user->role !== 'admin') {
            return back()->with('status', 'Reset password untuk akun ini dilakukan oleh Admin. Silakan hubungi Administrator sekolah.');
        }

        // Ambil email tujuan reset: utamakan dari email akun di database, fallback ke .env
        $resetEmail = $user->email ?: env('ADMIN_RESET_EMAIL');

        if (! $resetEmail) {
            return back()->withErrors(['username' => 'Email reset password belum diatur pada akun admin. Silakan login dan atur email di menu Profil.']);
        }

        // Pastikan kolom email user sinkron
        if ($user->email !== $resetEmail) {
            $user->update(['email' => $resetEmail]);
        }

        try {
            $status = Password::sendResetLink(['email' => $resetEmail]);

            return $status === Password::RESET_LINK_SENT
                ? back()->with('status', 'Link reset password telah dikirim ke email: ' . $resetEmail)
                : back()->withErrors(['username' => __($status)]);
        } catch (\Throwable $e) {
            report($e);

            // Jika koneksi SMTP gagal (misal port 465/587 diblokir oleh provider internet/ISP):
            // Buat token reset password secara langsung agar Admin tetap bisa melanjutkan reset password
            $token = Password::broker()->createToken($user);
            $resetUrl = route('password.reset', ['token' => $token, 'email' => $resetEmail]);

            return back()->with('status_fallback', [
                'email' => $resetEmail,
                'url' => $resetUrl,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
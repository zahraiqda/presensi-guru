<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        $cooldownRemaining = 0;
        $adminUser = User::where('role', 'admin')->first();
        if ($adminUser) {
            $resetEmail = $adminUser->email ?: env('ADMIN_RESET_EMAIL');
            if ($resetEmail) {
                $record = DB::table('password_reset_tokens')->where('email', $resetEmail)->first();
                if ($record && $record->created_at) {
                    $secondsAgo = now()->diffInSeconds(Carbon::parse($record->created_at));
                    if ($secondsAgo < 60) {
                        $cooldownRemaining = 60 - $secondsAgo;
                    }
                }
            }
        }

        if (session('cooldown_triggered')) {
            $cooldownRemaining = max($cooldownRemaining, session('cooldown_triggered'));
        }

        return view('auth.forgot-password', compact('cooldownRemaining'));
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

            if ($status === Password::RESET_LINK_SENT) {
                return back()
                    ->with('status', 'Link reset password telah dikirim ke email: ' . $resetEmail . '. Link ini hanya aktif selama 60 detik.')
                    ->with('cooldown_triggered', 60);
            }

            $pesan = match ($status) {
                Password::RESET_THROTTLED => 'Terlalu banyak permintaan reset password dalam waktu singkat. Harap tunggu waktu jeda (cooldown 60 detik) selesai sebelum mencoba kembali.',
                Password::INVALID_USER => 'Pengguna dengan email tersebut tidak ditemukan.',
                default => __($status),
            };

            return back()->withErrors(['username' => $pesan]);
        } catch (\Throwable $e) {
            report($e);

            // Jika koneksi SMTP gagal (misal port 465/587 diblokir oleh provider internet/ISP):
            // Buat token reset password secara langsung agar Admin tetap bisa melanjutkan reset password
            $token = Password::broker()->createToken($user);
            $resetUrl = route('password.reset', ['token' => $token, 'email' => $resetEmail]);

            return back()
                ->with('status_fallback', [
                    'email' => $resetEmail,
                    'url' => $resetUrl,
                    'error' => $e->getMessage(),
                ])
                ->with('cooldown_triggered', 60);
        }
    }
}
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

        // Ambil email tujuan reset dari .env
        $resetEmail = env('ADMIN_RESET_EMAIL');

        if (! $resetEmail) {
            return back()->withErrors(['username' => 'Email reset password belum dikonfigurasi. Hubungi pengembang sistem untuk mengisi ADMIN_RESET_EMAIL di file .env.']);
        }

        // Update email user admin agar sesuai dengan ADMIN_RESET_EMAIL di .env
        $user->update(['email' => $resetEmail]);

        $status = Password::sendResetLink(['email' => $resetEmail]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset password telah dikirim ke email: ' . $resetEmail)
            : back()->withErrors(['username' => __($status)]);
    }
}
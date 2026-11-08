<?php

use App\Http\Controllers\Admin\ArsipController as AdminArsipController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KepsekController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\PresensiController as GuruPresensiController;
use App\Http\Controllers\Kepsek\ArsipController as KepsekArsipController;
use App\Http\Controllers\Kepsek\DashboardController as KepsekDashboardController;
use App\Http\Controllers\Kepsek\LaporanController as KepsekLaporanController;
use App\Http\Controllers\Kepsek\LogRiwayatController as KepsekLogRiwayatController;
use App\Http\Controllers\IzinApprovalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Persetujuan Izin via Link WhatsApp untuk Kepala Sekolah (Tanpa login akun)
Route::get('/izin/persetujuan/{token}', [IzinApprovalController::class, 'show'])->name('izin.approval');
Route::post('/izin/persetujuan/{token}/proses', [IzinApprovalController::class, 'proses'])->name('izin.approval.proses');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->get('/dashboard', function () {
    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'kepala_sekolah' => redirect()->route('kepsek.dashboard'),
        'guru' => redirect()->route('guru.dashboard'),
    };
})->name('dashboard');

// Profil - semua role (Bab 13 PRD)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ------------------------- ADMIN -------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/data-guru', [GuruController::class, 'index'])->name('guru.index');
    Route::get('/data-guru/create', [GuruController::class, 'create'])->name('guru.create');
    Route::post('/data-guru', [GuruController::class, 'store'])->name('guru.store');
    Route::get('/data-guru/{guru}/edit', [GuruController::class, 'edit'])->name('guru.edit');
    Route::put('/data-guru/{guru}', [GuruController::class, 'update'])->name('guru.update');
    Route::delete('/data-guru/{guru}', [GuruController::class, 'destroy'])->name('guru.destroy');
    Route::post('/data-guru/import', [GuruController::class, 'import'])->name('guru.import');

    Route::get('/rekap-laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/rekap-laporan/jam-kerja', [LaporanController::class, 'updateJamKerja'])->name('laporan.jam-kerja');
    Route::post('/rekap-laporan/kosongkan', [LaporanController::class, 'kosongkanPresensi'])->name('laporan.kosongkan');
    Route::get('/rekap-laporan/pdf', [LaporanController::class, 'downloadPdf'])->name('laporan.pdf');

    Route::get('/arsip-presensi', [AdminArsipController::class, 'index'])->name('arsip.index');
    Route::get('/arsip-presensi/{arsip}', [AdminArsipController::class, 'show'])->name('arsip.show');
    Route::get('/arsip-presensi/{arsip}/pdf', [AdminArsipController::class, 'downloadPdf'])->name('arsip.pdf');
    Route::post('/arsip-presensi/{arsip}/restore', [AdminArsipController::class, 'restore'])->name('arsip.restore');
    Route::delete('/arsip-presensi/{arsip}', [AdminArsipController::class, 'destroy'])->name('arsip.destroy');

    Route::get('/kepala-sekolah', [KepsekController::class, 'index'])->name('kepsek.index');
    Route::put('/kepala-sekolah/{user}/password', [KepsekController::class, 'updatePassword'])->name('kepsek.password');
    Route::put('/kepala-sekolah/{user}/profile', [KepsekController::class, 'updateProfile'])->name('kepsek.profile');
});

// -------------------- KEPALA SEKOLAH ---------------------
Route::middleware(['auth', 'role:kepala_sekolah'])->prefix('kepsek')->name('kepsek.')->group(function () {
    Route::get('/dashboard', [KepsekDashboardController::class, 'index'])->name('dashboard');

    Route::get('/rekap-laporan', [KepsekLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/rekap-laporan/pdf', [KepsekLaporanController::class, 'downloadPdf'])->name('laporan.pdf');

    Route::get('/arsip-presensi', [KepsekArsipController::class, 'index'])->name('arsip.index');
    Route::get('/arsip-presensi/{arsip}', [KepsekArsipController::class, 'show'])->name('arsip.show');
    Route::get('/arsip-presensi/{arsip}/pdf', [KepsekArsipController::class, 'downloadPdf'])->name('arsip.pdf');

    Route::get('/log-riwayat', [KepsekLogRiwayatController::class, 'index'])->name('log.index');
});

// ---------------------------- GURU ----------------------------
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::post('/presensi/masuk', [GuruPresensiController::class, 'absenMasuk'])->name('presensi.masuk');
    Route::post('/presensi/pulang', [GuruPresensiController::class, 'absenPulang'])->name('presensi.pulang');
    Route::post('/presensi/sakit', [GuruPresensiController::class, 'sakit'])->name('presensi.sakit');
    Route::post('/presensi/izin', [GuruPresensiController::class, 'izin'])->name('presensi.izin');
    Route::put('/presensi/{presensi}', [GuruPresensiController::class, 'update'])->name('presensi.update');
    Route::delete('/presensi/{presensi}', [GuruPresensiController::class, 'destroy'])->name('presensi.destroy');
});

require __DIR__.'/auth.php';
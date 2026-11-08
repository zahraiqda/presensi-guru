<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PresensiController extends Controller
{
    public function absenMasuk(Request $request): RedirectResponse
    {
        $request->validate(['latitude' => ['required', 'numeric'], 'longitude' => ['required', 'numeric']]);

        $guru = Auth::user()->guru;
        $hariIni = today()->toDateString();

        if (! $guru) {
            return back()->with('error', 'Data guru tidak ditemukan.');
        }

        $sudahMasuk = Presensi::where('guru_id', $guru->id)->whereDate('tanggal', $hariIni)->where('status', 'masuk')->exists();

        if ($sudahMasuk) {
            return back()->with('error', 'Anda sudah melakukan Absen Masuk hari ini.');
        }

        $pengaturan = Pengaturan::aktif();

        [$bolehMasuk, $pesanMasuk] = $pengaturan->bolehAbsenMasuk();
        if (! $bolehMasuk) {
            return back()->with('error', $pesanMasuk);
        }

        $jarak = round($pengaturan->jarakDari((float) $request->latitude, (float) $request->longitude));

        if (! $pengaturan->dalamRadius((float) $request->latitude, (float) $request->longitude)) {
            return back()->with('error', "Presensi ditolak. Anda berada di luar radius area yang ditentukan (Jarak Anda: {$jarak} meter, maksimal: {$pengaturan->radius} meter).");
        }

        Presensi::create([
            'guru_id' => $guru->id,
            'tanggal' => $hariIni,
            'waktu' => now()->format('H:i:s'),
            'status' => 'masuk',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('status', "Absen Masuk berhasil dicatat (Jarak: {$jarak} m).");
    }

    public function absenPulang(Request $request): RedirectResponse
    {
        $request->validate(['latitude' => ['required', 'numeric'], 'longitude' => ['required', 'numeric']]);

        $guru = Auth::user()->guru;
        $hariIni = today()->toDateString();

        if (! $guru) {
            return back()->with('error', 'Data guru tidak ditemukan.');
        }

        $sudahMasuk = Presensi::where('guru_id', $guru->id)->whereDate('tanggal', $hariIni)->where('status', 'masuk')->exists();

        if (! $sudahMasuk) {
            return back()->with('error', 'Anda belum melakukan Absen Masuk hari ini.');
        }

        $sudahPulang = Presensi::where('guru_id', $guru->id)->whereDate('tanggal', $hariIni)->where('status', 'pulang')->exists();

        if ($sudahPulang) {
            return back()->with('error', 'Anda sudah melakukan Absen Pulang hari ini.');
        }

        $pengaturan = Pengaturan::aktif();

        [$bolehPulang, $pesanPulang] = $pengaturan->bolehAbsenPulang();
        if (! $bolehPulang) {
            return back()->with('error', $pesanPulang);
        }

        $jarak = round($pengaturan->jarakDari((float) $request->latitude, (float) $request->longitude));

        if (! $pengaturan->dalamRadius((float) $request->latitude, (float) $request->longitude)) {
            return back()->with('error', "Presensi ditolak. Anda berada di luar radius area yang ditentukan (Jarak Anda: {$jarak} meter, maksimal: {$pengaturan->radius} meter).");
        }

        Presensi::create([
            'guru_id' => $guru->id,
            'tanggal' => $hariIni,
            'waktu' => now()->format('H:i:s'),
            'status' => 'pulang',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('status', "Absen Pulang berhasil dicatat (Jarak: {$jarak} m).");
    }

    public function sakit(Request $request): RedirectResponse
    {
        $request->validate([
            'keterangan' => ['required', 'string', 'max:255'],
            'bukti_sakit' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            'keterangan.required' => 'Keterangan sakit wajib diisi.',
            'bukti_sakit.required' => 'Surat dokter / foto bukti sakit wajib diunggah.',
            'bukti_sakit.file' => 'File bukti sakit harus berupa file yang valid.',
            'bukti_sakit.mimes' => 'Format file bukti harus berupa JPG, JPEG, atau PNG.',
            'bukti_sakit.max' => 'Ukuran file bukti maksimal 5 MB.',
        ]);

        $guru = Auth::user()->guru;
        $hariIni = today()->toDateString();

        if (! $guru) {
            return back()->with('error', 'Data guru tidak ditemukan.');
        }

        $sudahAdaStatus = Presensi::where('guru_id', $guru->id)
            ->whereDate('tanggal', $hariIni)
            ->whereIn('status', ['masuk', 'sakit', 'izin'])
            ->exists();

        if ($sudahAdaStatus) {
            return back()->with('error', 'Anda sudah memiliki catatan presensi untuk hari ini.');
        }

        $path = $request->file('bukti_sakit')->store('bukti_sakit', 'public');

        Presensi::create([
            'guru_id' => $guru->id,
            'tanggal' => $hariIni,
            'waktu' => now()->format('H:i:s'),
            'status' => 'sakit',
            'keterangan' => $request->keterangan,
            'bukti_sakit' => $path,
        ]);

        return back()->with('status', 'Keterangan Sakit beserta bukti berhasil dicatat.');
    }

    public function izin(Request $request): RedirectResponse
    {
        $request->validate([
            'keterangan' => ['required', 'string', 'max:255'],
            'bukti_izin' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'keterangan.required' => 'Keterangan alasan izin wajib diisi.',
            'bukti_izin.file' => 'File bukti surat izin harus berupa file yang valid.',
            'bukti_izin.mimes' => 'Format file bukti harus berupa JPG, JPEG, PNG, atau PDF.',
            'bukti_izin.max' => 'Ukuran file bukti maksimal 5 MB.',
        ]);

        $guru = Auth::user()->guru;
        $hariIni = today()->toDateString();

        if (! $guru) {
            return back()->with('error', 'Data guru tidak ditemukan.');
        }

        $sudahAdaStatus = Presensi::where('guru_id', $guru->id)
            ->whereDate('tanggal', $hariIni)
            ->whereIn('status', ['masuk', 'sakit', 'izin'])
            ->exists();

        if ($sudahAdaStatus) {
            return back()->with('error', 'Anda sudah memiliki catatan presensi untuk hari ini.');
        }

        $buktiIzinPath = null;
        if ($request->hasFile('bukti_izin')) {
            $buktiIzinPath = $request->file('bukti_izin')->store('bukti_izin', 'public');
        }

        $tokenIzin = Str::random(40);

        $presensi = Presensi::create([
            'guru_id' => $guru->id,
            'tanggal' => $hariIni,
            'waktu' => now()->format('H:i:s'),
            'status' => 'izin',
            'keterangan' => $request->keterangan,
            'bukti_izin' => $buktiIzinPath,
            'token_izin' => $tokenIzin,
            'approval_status' => 'menunggu',
        ]);

        $pengaturan = Pengaturan::aktif();
        $waLink = $presensi->wa_link;

        if ($waLink) {
            return redirect()->away($waLink);
        }

        return back()->with('status', 'Permohonan izin berhasil dicatat, namun nomor WhatsApp Kepala Sekolah belum diatur oleh Admin.');
    }

    private function simpanIzinSakit(Request $request, string $status): RedirectResponse
    {
        $request->validate(['keterangan' => ['required', 'string', 'max:255']]);

        $guru = Auth::user()->guru;
        $hariIni = today()->toDateString();

        if (! $guru) {
            return back()->with('error', 'Data guru tidak ditemukan.');
        }

        $sudahAdaStatus = Presensi::where('guru_id', $guru->id)
            ->whereDate('tanggal', $hariIni)
            ->whereIn('status', ['masuk', 'sakit', 'izin'])
            ->exists();

        if ($sudahAdaStatus) {
            return back()->with('error', 'Anda sudah memiliki catatan presensi untuk hari ini.');
        }

        Presensi::create([
            'guru_id' => $guru->id,
            'tanggal' => $hariIni,
            'waktu' => now()->format('H:i:s'),
            'status' => $status,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('status', ucfirst($status) . ' berhasil dicatat.');
    }

    /**
     * Perbarui keterangan / berkas bukti presensi milik guru yang bersangkutan.
     */
    public function update(Request $request, Presensi $presensi): RedirectResponse
    {
        $guru = Auth::user()->guru;

        if (! $guru || $presensi->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data presensi ini.');
        }

        $request->validate([
            'keterangan' => ['nullable', 'string', 'max:255'],
            'bukti_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'bukti_file.file' => 'Berkas bukti harus berupa file yang valid.',
            'bukti_file.mimes' => 'Format file bukti harus berupa JPG, JPEG, PNG, atau PDF.',
            'bukti_file.max' => 'Ukuran file bukti maksimal 5 MB.',
        ]);

        $dataUpdate = [
            'keterangan' => $request->keterangan,
        ];

        if ($request->hasFile('bukti_file')) {
            if ($presensi->status === 'sakit') {
                if ($presensi->bukti_sakit) {
                    Storage::disk('public')->delete($presensi->bukti_sakit);
                }
                $dataUpdate['bukti_sakit'] = $request->file('bukti_file')->store('bukti_sakit', 'public');
            } elseif ($presensi->status === 'izin') {
                if ($presensi->bukti_izin) {
                    Storage::disk('public')->delete($presensi->bukti_izin);
                }
                $dataUpdate['bukti_izin'] = $request->file('bukti_file')->store('bukti_izin', 'public');
            }
        }

        $presensi->update($dataUpdate);

        return back()->with('status', 'Data presensi berhasil diperbarui.');
    }

    /**
     * Hapus data presensi milik guru yang bersangkutan.
     */
    public function destroy(Presensi $presensi): RedirectResponse
    {
        $guru = Auth::user()->guru;

        if (! $guru || $presensi->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus data presensi ini.');
        }

        if ($presensi->bukti_sakit) {
            Storage::disk('public')->delete($presensi->bukti_sakit);
        }

        if ($presensi->bukti_izin) {
            Storage::disk('public')->delete($presensi->bukti_izin);
        }

        $presensi->delete();

        return back()->with('status', 'Data presensi berhasil dihapus.');
    }
}
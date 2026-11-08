<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $fillable = [
        'latitude_sekolah', 'longitude_sekolah', 'radius', 'no_wa_kepsek',
        'jam_masuk_mulai', 'jam_masuk_toleransi', 'jam_masuk_selesai',
        'jam_pulang_minimal', 'jam_pulang_selesai',
    ];

    public static function aktif(): self
    {
        return static::firstOrFail();
    }

    public function getNoWaKepsekFormattedAttribute(): ?string
    {
        if (empty($this->no_wa_kepsek)) {
            return null;
        }

        $cleaned = preg_replace('/[^0-9]/', '', (string) $this->no_wa_kepsek);

        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }

        if (str_starts_with($cleaned, '8')) {
            return '62' . $cleaned;
        }

        return $cleaned;
    }

    public function buildWhatsAppUrl(string $message): ?string
    {
        $nomor = $this->no_wa_kepsek_formatted;
        if (! $nomor) {
            return null;
        }

        return 'https://api.whatsapp.com/send?phone=' . $nomor . '&text=' . rawurlencode($message);
    }

    public function bolehAbsenMasuk(?string $waktu = null): array
    {
        $waktu = $waktu ?? now()->format('H:i:s');
        $mulai = $this->jam_masuk_mulai ?? '06:00:00';
        $selesai = $this->jam_masuk_selesai ?? '12:00:00';

        if ($waktu < $mulai) {
            $formatMulai = \Illuminate\Support\Carbon::parse($mulai)->format('H:i');
            return [false, "Presensi Masuk belum dibuka. Jam masuk dimulai pukul {$formatMulai} WIB."];
        }

        if ($waktu > $selesai) {
            $formatSelesai = \Illuminate\Support\Carbon::parse($selesai)->format('H:i');
            return [false, "Presensi Masuk telah ditutup untuk hari ini (Batas akhir pukul {$formatSelesai} WIB)."];
        }

        return [true, ''];
    }

    public function bolehAbsenPulang(?string $waktu = null): array
    {
        $waktu = $waktu ?? now()->format('H:i:s');
        $minimal = $this->jam_pulang_minimal ?? '15:00:00';
        $selesai = $this->jam_pulang_selesai ?? '19:00:00';

        if ($waktu < $minimal) {
            $formatMinimal = \Illuminate\Support\Carbon::parse($minimal)->format('H:i');
            return [false, "Presensi Pulang belum dibuka. Jam pulang minimal adalah pukul {$formatMinimal} WIB."];
        }

        if ($waktu > $selesai) {
            $formatSelesai = \Illuminate\Support\Carbon::parse($selesai)->format('H:i');
            return [false, "Presensi Pulang telah ditutup untuk hari ini (Batas akhir pukul {$formatSelesai} WIB)."];
        }

        return [true, ''];
    }

    public function jarakDari(float $lat, float $lng): float
    {
        $earthRadius = 6371000;

        $latFrom = deg2rad($this->latitude_sekolah);
        $lngFrom = deg2rad($this->longitude_sekolah);
        $latTo = deg2rad($lat);
        $lngTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $a = sin($latDelta / 2) ** 2 + cos($latFrom) * cos($latTo) * sin($lngDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function dalamRadius(float $lat, float $lng): bool
    {
        return $this->jarakDari($lat, $lng) <= $this->radius;
    }
}
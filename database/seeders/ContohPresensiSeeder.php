<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Presensi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ContohPresensiSeeder extends Seeder
{
    public function run(): void
    {
        $polaPerNik = [
            '3201234567890010' => 'rajin_lupa_pulang',
            '3201234567890011' => 'sering_telat',
            '3201234567890012' => 'durasi_pendek',
            '3201234567890013' => 'sering_sakit',
            '3201234567890014' => 'sering_izin',
            '3201234567890015' => 'normal',
        ];

        $lat = -6.9000000;
        $lng = 108.9500000;

        foreach ($polaPerNik as $nik => $pola) {
            $guru = Guru::where('nik', $nik)->first();

            if (! $guru) {
                $this->command?->warn("Guru dengan NIK {$nik} tidak ditemukan, dilewati.");
                continue;
            }

            $this->buatPresensi($guru, $pola, $lat, $lng);
        }

        // Buat data presensi hari ini agar dashboard langsung memiliki data realistis
        $hariIni = today()->toDateString();

        $g1 = Guru::where('nik', '3201234567890001')->first();
        if ($g1) {
            $this->simpanMasuk($g1, $hariIni, '06:50:00', $lat, $lng);
        }

        $g2 = Guru::where('nik', '3201234567890010')->first();
        if ($g2) {
            $this->simpanMasuk($g2, $hariIni, '07:05:00', $lat, $lng);
            $this->simpanPulang($g2, $hariIni, '15:10:00', $lat, $lng);
        }

        $g3 = Guru::where('nik', '3201234567890011')->first();
        if ($g3) {
            $this->simpanMasuk($g3, $hariIni, '07:12:00', $lat, $lng);
        }

        $g4 = Guru::where('nik', '3201234567890012')->first();
        if ($g4) {
            Presensi::firstOrCreate(
                ['guru_id' => $g4->id, 'tanggal' => $hariIni, 'status' => 'izin'],
                ['waktu' => '07:00:00', 'keterangan' => 'Dinas Luar Workshop Kurikulum']
            );
        }

        $g5 = Guru::where('nik', '3201234567890013')->first();
        if ($g5) {
            Presensi::firstOrCreate(
                ['guru_id' => $g5->id, 'tanggal' => $hariIni, 'status' => 'sakit'],
                ['waktu' => '07:00:00', 'keterangan' => 'Demam dan Flu']
            );
        }

        $g7 = Guru::where('nik', '3201234567890015')->first();
        if ($g7) {
            $this->simpanMasuk($g7, $hariIni, '06:40:00', $lat, $lng);
        }
    }

    private function buatPresensi(Guru $guru, string $pola, float $lat, float $lng): void
    {
        $tanggal = Carbon::yesterday();
        $hariDibuat = 0;

        while ($hariDibuat < 10) {
            if ($tanggal->isWeekend()) {
                $tanggal->subDay();
                continue;
            }

            $tgl = $tanggal->toDateString();

            switch ($pola) {
                case 'rajin_lupa_pulang':
                    $this->simpanMasuk($guru, $tgl, '06:45:00', $lat, $lng);
                    if ($hariDibuat % 3 !== 0) {
                        $this->simpanPulang($guru, $tgl, '15:30:00', $lat, $lng);
                    }
                    break;

                case 'sering_telat':
                    $this->simpanMasuk($guru, $tgl, '07:35:00', $lat, $lng);
                    $this->simpanPulang($guru, $tgl, '15:15:00', $lat, $lng);
                    break;

                case 'durasi_pendek':
                    $this->simpanMasuk($guru, $tgl, '07:10:00', $lat, $lng);
                    $this->simpanPulang($guru, $tgl, '10:30:00', $lat, $lng);
                    break;

                case 'sering_sakit':
                    if ($hariDibuat % 4 === 0) {
                        Presensi::firstOrCreate(
                            ['guru_id' => $guru->id, 'tanggal' => $tgl, 'status' => 'sakit'],
                            ['waktu' => '07:00:00', 'keterangan' => 'Demam']
                        );
                    } else {
                        $this->simpanMasuk($guru, $tgl, '07:05:00', $lat, $lng);
                        $this->simpanPulang($guru, $tgl, '15:05:00', $lat, $lng);
                    }
                    break;

                case 'sering_izin':
                    if ($hariDibuat % 3 === 0) {
                        Presensi::firstOrCreate(
                            ['guru_id' => $guru->id, 'tanggal' => $tgl, 'status' => 'izin'],
                            ['waktu' => '07:00:00', 'keterangan' => 'Keperluan keluarga']
                        );
                    } else {
                        $this->simpanMasuk($guru, $tgl, '07:00:00', $lat, $lng);
                        $this->simpanPulang($guru, $tgl, '15:10:00', $lat, $lng);
                    }
                    break;

                default:
                    $this->simpanMasuk($guru, $tgl, '06:55:00', $lat, $lng);
                    $this->simpanPulang($guru, $tgl, '15:05:00', $lat, $lng);
                    break;
            }

            $tanggal->subDay();
            $hariDibuat++;
        }
    }

    private function simpanMasuk(Guru $guru, string $tanggal, string $waktu, float $lat, float $lng): void
    {
        Presensi::firstOrCreate(
            ['guru_id' => $guru->id, 'tanggal' => $tanggal, 'status' => 'masuk'],
            ['waktu' => $waktu, 'latitude' => $lat + 0.0002, 'longitude' => $lng + 0.0002]
        );
    }

    private function simpanPulang(Guru $guru, string $tanggal, string $waktu, float $lat, float $lng): void
    {
        Presensi::firstOrCreate(
            ['guru_id' => $guru->id, 'tanggal' => $tanggal, 'status' => 'pulang'],
            ['waktu' => $waktu, 'latitude' => $lat - 0.0002, 'longitude' => $lng - 0.0002]
        );
    }
}
<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ContohAkunSeeder extends Seeder
{
    public function run(): void
    {
        $kepsek = User::firstOrCreate(
            ['username' => 'kepsek'],
            [
                'nama' => 'Faatih Ghozwatul Fikri, S.T., M.Pd.',
                'email' => 'kepsek@alamanah.sch.id',
                'password' => Hash::make('kepsek123'),
                'role' => 'kepala_sekolah',
            ]
        );

        // Kalau akun sudah ada, pastikan namanya ikut diperbarui
        $kepsek->update(['nama' => 'Faatih Ghozwatul Fikri, S.T., M.Pd.']);

        $teachers = [
            ['nik' => '3201234567891001', 'username' => 'guru1', 'nama' => 'Ahmad Fauzi, S.Pd.'],
            ['nik' => '3201234567891002', 'username' => 'guru2', 'nama' => 'Budi Santoso, S.Kom.'],
            ['nik' => '3201234567891003', 'username' => 'guru3', 'nama' => 'Siti Aminah, S.Pd.I.'],
            ['nik' => '3201234567891004', 'username' => 'guru4', 'nama' => 'Eko Prasetyo, S.T.'],
            ['nik' => '3201234567891005', 'username' => 'guru5', 'nama' => 'Dewi Lestari, M.Pd.'],
            ['nik' => '3201234567891006', 'username' => 'guru6', 'nama' => 'Hendra Kurniawan, S.Pd.'],
            ['nik' => '3201234567891007', 'username' => 'guru7', 'nama' => 'Nurul Hidayah, S.Ag.'],
        ];

        foreach ($teachers as $item) {
            $user = User::firstOrCreate(
                ['username' => $item['username']],
                [
                    'nama' => $item['nama'],
                    'email' => null,
                    'password' => Hash::make('guru123'),
                    'role' => 'guru',
                ]
            );

            Guru::firstOrCreate(
                ['user_id' => $user->id],
                ['nik' => $item['nik'], 'nama' => $item['nama']]
            );
        }
    }
}
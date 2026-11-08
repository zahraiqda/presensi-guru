<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminPengaturanSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'email' => env('ADMIN_RESET_EMAIL', 'admin@alamanah.sch.id'),
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        Pengaturan::firstOrCreate(
            ['id' => 1],
            [
                'latitude_sekolah' => -6.9000000,
                'longitude_sekolah' => 108.9500000,
                'radius' => 60,
                'jam_masuk_mulai' => '06:00:00',
                'jam_masuk_toleransi' => '07:15:00',
                'jam_masuk_selesai' => '12:00:00',
                'jam_pulang_minimal' => '15:00:00',
                'jam_pulang_selesai' => '19:00:00',
            ]
        );
    }
}
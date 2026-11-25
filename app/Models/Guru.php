<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = ['user_id', 'nik', 'nama', 'mata_pelajaran'];

    public function getLabelMapelAttribute(): string
    {
        $val = trim((string) $this->mata_pelajaran);

        if (empty($val)) {
            return 'Guru / Staf';
        }

        // Tampilkan persis apa adanya sesuai inputan/pilihan admin tanpa ditambah kata "Guru" otomatis
        return $val;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function riwayatTerakhir(int $hari = 5)
    {
        return $this->presensi()->orderByDesc('tanggal')->orderByDesc('waktu')->take($hari);
    }
}
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
        if (empty($this->mata_pelajaran)) {
            return 'Tenaga Pengajar';
        }

        if (preg_match('/^guru\b/i', trim($this->mata_pelajaran))) {
            return trim($this->mata_pelajaran);
        }

        return 'Guru ' . trim($this->mata_pelajaran);
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
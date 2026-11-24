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
            return 'Tenaga Pendidik / Staf';
        }

        // Jika sudah berupa nama jabatan struktural, staf, atau berawalan Guru
        if (preg_match('/^(guru|waka|staf|staff|kepala|kaprog|bendahara|operator|laboran|toolman|teknisi|pustakawan|penjaga|satpam|koordinator|pembina)\b/i', $val)) {
            return $val;
        }

        return 'Guru ' . $val;
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
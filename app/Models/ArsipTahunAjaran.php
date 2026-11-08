<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArsipTahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'arsip_tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'nama_label',
        'keterangan',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_presensi',
        'total_hadir',
        'total_terlambat',
        'total_sakit',
        'total_izin',
        'diarsipkan_oleh',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function presensi(): HasMany
    {
        return $this->hasMany(ArsipPresensi::class, 'arsip_tahun_ajaran_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diarsipkan_oleh');
    }

    public function getPeriodeTeksAttribute(): string
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            return $this->tanggal_mulai->isoFormat('D MMM Y') . ' s/d ' . $this->tanggal_selesai->isoFormat('D MMM Y');
        } elseif ($this->tanggal_mulai) {
            return 'Mulai ' . $this->tanggal_mulai->isoFormat('D MMM Y');
        }
        return 'Tidak ada catatan tanggal';
    }

    public function getSemesterLabelAttribute(): string
    {
        return match ($this->semester) {
            'ganjil' => 'Semester Ganjil',
            'genap' => 'Semester Genap',
            default => '1 Tahun Penuh',
        };
    }
}

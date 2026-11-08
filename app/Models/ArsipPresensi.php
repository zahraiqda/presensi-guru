<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipPresensi extends Model
{
    use HasFactory;

    protected $table = 'arsip_presensi';

    protected $fillable = [
        'arsip_tahun_ajaran_id',
        'guru_id',
        'nama_guru',
        'nip_guru',
        'mata_pelajaran',
        'tanggal',
        'waktu',
        'status',
        'latitude',
        'longitude',
        'keterangan',
        'bukti_sakit',
        'token_izin',
        'approval_status',
        'approved_at',
        'bukti_izin',
        'catatan_kepsek',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(ArsipTahunAjaran::class, 'arsip_tahun_ajaran_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function getBuktiSakitUrlAttribute(): ?string
    {
        return $this->bukti_sakit ? asset('storage/' . $this->bukti_sakit) : null;
    }

    public function getBuktiIzinUrlAttribute(): ?string
    {
        return $this->bukti_izin ? asset('storage/' . $this->bukti_izin) : null;
    }
}

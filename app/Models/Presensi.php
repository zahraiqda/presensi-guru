<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'guru_id', 'tanggal', 'waktu', 'status', 'latitude', 'longitude',
        'keterangan', 'bukti_sakit', 'token_izin', 'approval_status',
        'approved_at', 'bukti_izin', 'catatan_kepsek',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
    ];

    public function getBuktiSakitUrlAttribute(): ?string
    {
        return $this->bukti_sakit ? asset('storage/' . $this->bukti_sakit) : null;
    }

    public function getBuktiIzinUrlAttribute(): ?string
    {
        return $this->bukti_izin ? asset('storage/' . $this->bukti_izin) : null;
    }

    public function getApprovalUrlAttribute(): ?string
    {
        return $this->token_izin ? route('izin.approval', ['token' => $this->token_izin]) : null;
    }

    public function getPesanWhatsAppAttribute(): string
    {
        $guru = $this->guru;
        $nama = $guru?->nama ?? 'Guru';
        $nip = $guru?->nip ? ' (NIP: ' . $guru->nip . ')' : '';
        $mapel = $guru?->mata_pelajaran ? ' - Guru ' . $guru->mata_pelajaran : '';
        $tanggal = $this->tanggal ? $this->tanggal->isoFormat('dddd, D MMMM Y') : date('d/m/Y');
        $alasan = $this->keterangan ?? '-';
        $link = $this->approval_url;

        return "Assalamu'alaikum Wr. Wb. Bapak/Ibu Kepala Sekolah,\n\n" .
            "Saya mengajukan permohonan IZIN tidak dapat hadir di sekolah:\n\n" .
            "👤 *Nama:* {$nama}{$nip}{$mapel}\n" .
            "📅 *Hari/Tanggal:* {$tanggal}\n" .
            "📝 *Alasan/Keterangan:* {$alasan}\n\n" .
            "Mohon kesediaan Bapak/Ibu untuk memeriksa dan memberikan persetujuan melalui tautan resmi berikut:\n" .
            "👉 {$link}\n\n" .
            "Terima kasih atas perhatian dan kebijaksanaan Bapak/Ibu.\n" .
            "Wassalamu'alaikum Wr. Wb.";
    }

    public function getWaLinkAttribute(): ?string
    {
        $pengaturan = Pengaturan::aktif();
        return $pengaturan->buildWhatsAppUrl($this->pesan_whats_app);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function scopePeriode($query, string $mulai, string $sampai)
    {
        return $query->whereBetween('tanggal', [$mulai, $sampai]);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeHadir($query)
    {
        return $query->whereIn('status', ['masuk', 'pulang']);
    }
}
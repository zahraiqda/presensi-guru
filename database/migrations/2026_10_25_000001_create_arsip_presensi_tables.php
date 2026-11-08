<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('arsip_tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran'); // e.g. '2024/2025'
            $table->string('semester')->default('semua'); // 'semua', 'ganjil', 'genap'
            $table->string('nama_label'); // e.g. '2024/2025 (Penuh)' or '2024/2025 - Semester Ganjil'
            $table->text('keterangan')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->unsignedInteger('total_presensi')->default(0);
            $table->unsignedInteger('total_hadir')->default(0);
            $table->unsignedInteger('total_terlambat')->default(0);
            $table->unsignedInteger('total_sakit')->default(0);
            $table->unsignedInteger('total_izin')->default(0);
            $table->foreignId('diarsipkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('tahun_ajaran');
            $table->index('nama_label');
        });

        Schema::create('arsip_presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arsip_tahun_ajaran_id')->constrained('arsip_tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('guru_id')->nullable()->constrained('guru')->nullOnDelete();
            $table->string('nama_guru');
            $table->string('nip_guru')->nullable();
            $table->string('mata_pelajaran')->nullable();
            $table->date('tanggal');
            $table->time('waktu');
            $table->enum('status', ['masuk', 'pulang', 'sakit', 'izin']);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('keterangan')->nullable();
            $table->string('bukti_sakit')->nullable();
            $table->string('token_izin', 64)->nullable();
            $table->enum('approval_status', ['menunggu', 'disetujui', 'ditolak'])->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('bukti_izin')->nullable();
            $table->text('catatan_kepsek')->nullable();
            $table->timestamps();

            $table->index(['arsip_tahun_ajaran_id', 'tanggal']);
            $table->index(['arsip_tahun_ajaran_id', 'status']);
            $table->index(['arsip_tahun_ajaran_id', 'guru_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_presensi');
        Schema::dropIfExists('arsip_tahun_ajaran');
    }
};

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
        Schema::table('presensi', function (Blueprint $table) {
            if (! Schema::hasColumn('presensi', 'token_izin')) {
                $table->string('token_izin', 64)->nullable()->unique()->after('keterangan');
            }
            if (! Schema::hasColumn('presensi', 'approval_status')) {
                $table->enum('approval_status', ['menunggu', 'disetujui', 'ditolak'])->nullable()->after('token_izin');
            }
            if (! Schema::hasColumn('presensi', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approval_status');
            }
            if (! Schema::hasColumn('presensi', 'bukti_izin')) {
                $table->string('bukti_izin')->nullable()->after('approved_at');
            }
            if (! Schema::hasColumn('presensi', 'catatan_kepsek')) {
                $table->text('catatan_kepsek')->nullable()->after('bukti_izin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $cols = [];
            foreach (['bukti_izin', 'catatan_kepsek', 'approved_at', 'approval_status', 'token_izin'] as $col) {
                if (Schema::hasColumn('presensi', $col)) {
                    $cols[] = $col;
                }
            }
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};

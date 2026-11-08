<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->time('jam_masuk_mulai')->default('06:00:00')->after('radius');
            $table->time('jam_masuk_selesai')->default('12:00:00')->after('jam_masuk_toleransi');
            $table->time('jam_pulang_selesai')->default('19:00:00')->after('jam_pulang_minimal');
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->dropColumn(['jam_masuk_mulai', 'jam_masuk_selesai', 'jam_pulang_selesai']);
        });
    }
};

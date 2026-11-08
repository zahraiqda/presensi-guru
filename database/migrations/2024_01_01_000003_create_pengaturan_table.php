<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude_sekolah', 10, 7);
            $table->decimal('longitude_sekolah', 10, 7);
            $table->unsignedInteger('radius')->comment('radius dalam meter');
            $table->time('jam_masuk_toleransi');
            $table->time('jam_pulang_minimal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
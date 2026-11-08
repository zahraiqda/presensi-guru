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
        if (! Schema::hasColumn('pengaturan', 'no_wa_kepsek')) {
            Schema::table('pengaturan', function (Blueprint $table) {
                $table->string('no_wa_kepsek', 25)->nullable()->after('radius');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pengaturan', 'no_wa_kepsek')) {
            Schema::table('pengaturan', function (Blueprint $table) {
                $table->dropColumn('no_wa_kepsek');
            });
        }
    }
};

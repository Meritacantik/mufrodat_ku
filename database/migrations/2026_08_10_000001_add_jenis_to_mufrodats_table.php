<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mufrodats', function (Blueprint $table) {
            // Jenis kata: Mufrad, Fi'il Madhi, Fi'il Mudhari', Fi'il Amr,
            // Masdar, Mubtada - Khabar, dsb. (lihat DATA_KOSAKATA_FRESH.xlsx)
            $table->string('jenis')->nullable()->after('arti');
        });
    }

    public function down(): void
    {
        Schema::table('mufrodats', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};

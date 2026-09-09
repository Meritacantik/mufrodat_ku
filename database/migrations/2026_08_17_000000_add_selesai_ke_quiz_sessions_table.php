<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_sessions', function (Blueprint $table) {
            // Penanda apakah sesi ini sudah "disetor" ke total_sesi milik Score.
            // Mencegah total_sesi dobel-hitung kalau halaman hasil dibuka ulang
            // (refresh / tombol back browser).
            $table->boolean('selesai')->default(false)->after('total_skor');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_sessions', function (Blueprint $table) {
            $table->dropColumn('selesai');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Log aktivitas siswa untuk ditampilkan di Dashboard ("Aktivitas
        // terbaru"). Aktivitas jenis 'kuis' TIDAK dicatat di sini - itu
        // sudah tersedia lewat tabel quiz_sessions dan diambil langsung
        // dari situ. Tabel ini khusus mencatat jenis aktivitas lain yang
        // belum ada tabelnya sendiri: pencarian kata & kenaikan peringkat.
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('tipe', ['cari', 'peringkat']);
            $table->string('deskripsi', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

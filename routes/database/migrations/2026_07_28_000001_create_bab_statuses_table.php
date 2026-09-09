<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menyimpan status aktif/draf per (kelas, bab), dipakai oleh
        // fitur admin "Kelola Sesi Kuis" untuk mengatur bab mana yang
        // boleh dikerjakan siswa pada halaman Pilih Kuis.
        Schema::create('bab_statuses', function (Blueprint $table) {
            $table->id();
            $table->enum('kelas', ['VII', 'VIII', 'IX']);
            $table->integer('bab');
            $table->enum('status', ['aktif', 'draf'])->default('aktif');
            $table->timestamps();
            $table->unique(['kelas', 'bab']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bab_statuses');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Data uji variasi typo untuk evaluasi algoritma (Bab III - teknik
        // analisis data: 50-100 data uji, confusion matrix one-vs-rest).
        // Terpisah dari quiz_answers karena quiz_answers berisi jawaban
        // SISWA SUNGGUHAN (dipakai untuk skor & leaderboard), sedangkan
        // tabel ini berisi data uji SINTETIS dengan ground truth yang
        // sudah diketahui, khusus untuk mengukur akurasi algoritma.
        Schema::create('algorithm_test_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mufrodat_id')->constrained()->onDelete('cascade');
            $table->string('jawaban_uji');
            // identik | typo_1_karakter | typo_2_karakter | kata_acak
            $table->string('jenis_variasi', 30);
            $table->enum('status_seharusnya', ['BENAR', 'TYPO', 'SALAH']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('algorithm_test_cases');
    }
};

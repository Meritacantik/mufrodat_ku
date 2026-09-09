<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('mufrodat_id')->constrained()->onDelete('cascade');
            $table->string('jawaban_siswa');
            $table->string('jawaban_referensi');
            $table->integer('jarak_levenshtein');
            $table->enum('status', ['BENAR', 'TYPO', 'SALAH']);
            $table->integer('poin')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};

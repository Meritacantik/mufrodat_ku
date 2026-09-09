<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('kelas', ['VII', 'VIII', 'IX']);
            $table->integer('bab');
            $table->integer('total_soal')->default(10);
            $table->integer('total_benar')->default(0);
            $table->integer('total_typo')->default(0);
            $table->integer('total_salah')->default(0);
            $table->integer('total_skor')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_sessions');
    }
};

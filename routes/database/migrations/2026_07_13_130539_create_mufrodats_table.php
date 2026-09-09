<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mufrodats', function (Blueprint $table) {
            $table->id();
            $table->string('arab');
            $table->string('latin');
            $table->string('arti');
            $table->enum('kelas', ['VII', 'VIII', 'IX']);
            $table->integer('bab')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mufrodats');
    }
};

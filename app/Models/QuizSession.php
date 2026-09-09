<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSession extends Model
{
    protected $fillable = [
        'user_id',
        'kelas',
        'bab',
        'total_soal',
        'total_benar',
        'total_typo',
        'total_salah',
        'total_skor'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }
}

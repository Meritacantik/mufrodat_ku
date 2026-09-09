<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    protected $fillable = [
        'quiz_session_id',
        'mufrodat_id',
        'jawaban_siswa',
        'jawaban_referensi',
        'jarak_levenshtein',
        'status',
        'poin'
    ];

    public function session()
    {
        return $this->belongsTo(QuizSession::class, 'quiz_session_id');
    }

    public function mufrodat()
    {
        return $this->belongsTo(Mufrodat::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlgorithmTestCase extends Model
{
    protected $fillable = [
        'mufrodat_id',
        'jawaban_uji',
        'jenis_variasi',
        'status_seharusnya',
    ];

    public function mufrodat()
    {
        return $this->belongsTo(Mufrodat::class);
    }
}

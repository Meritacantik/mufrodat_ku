<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mufrodat extends Model
{
    protected $fillable = [
        'arab',
        'latin',
        'arti',
        'jenis',
        'audio',
        'kelas',
        'bab'
    ];
}

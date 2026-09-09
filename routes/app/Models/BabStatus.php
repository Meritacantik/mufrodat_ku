<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BabStatus extends Model
{
    protected $fillable = [
        'kelas',
        'bab',
        'status',
    ];
}

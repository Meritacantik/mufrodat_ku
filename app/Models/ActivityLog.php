<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'tipe', 'deskripsi'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

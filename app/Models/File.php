<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'path',
        'original_name',
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }
}

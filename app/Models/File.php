<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'path',
        'original_name',
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }
}

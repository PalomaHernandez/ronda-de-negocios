<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'url',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
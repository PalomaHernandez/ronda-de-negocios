<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    protected $fillable = [
        'inscription_date',
        'interests',
        'products_services',
        'remaining_meetings'
    ];

	protected $casts = [
        'inscription_date'
	];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
    
    public function images() {
        return $this->hasMany(Image::class);
    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\Notification;

class Registration extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'inscription_date',
        'interests',
        'products_services',
        'remaining_meetings'
    ];

	protected $casts = [
        'inscription_date' => 'datetime',
	];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}

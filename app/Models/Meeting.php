<?php

namespace App\Models;

use App\Patterns\State\Meeting\MeetingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Meeting extends Model
{
    protected $fillable = [
        'reason',
        'requester_role',
        'status',
        'assigned_table',
        'time',
    ];

    protected $casts = [
        'time' => 'datetime',
	];
    // ✅ Convierte el string de la base de datos en un objeto Enum automáticamente
    public function getStatusAttribute($value): MeetingStatus
    {
        return MeetingStatus::tryFrom($value) ?? MeetingStatus::DEFAULT_STATUS; // Evita errores si el valor no existe
    }

    // ✅ Convierte la Enum en un string antes de guardarla en la base de datos
    public function setStatusAttribute(MeetingStatus $value)
    {
        $this->attributes['status'] = $value->value;
    }
    
    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function requester(){
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function status():Attribute {
		return Attribute::make(
            get: fn () => MeetingStatus::from($this->attributes['status'])
        );
	}
}

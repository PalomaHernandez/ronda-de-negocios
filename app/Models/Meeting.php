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
        'status' => MeetingStatus::class,
	];

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

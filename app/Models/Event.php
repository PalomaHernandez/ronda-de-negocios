<?php

namespace App\Models;

use App\Patterns\State\Event\EventStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Event extends Model {
    protected $fillable = [
		'title',
		'description',
		'starts_at',
		'ends_at',
		'date',
		'meeting_duration',
		'time_between_meetings',
		'inscription_end_date',
		'matching_end_date',
		'logo_path',
        'responsible_id',
	];

	protected $casts = [
		'date' => 'date',
		'starts_at' => 'datetime:H:i:s',
		'ends_at' => 'datetime:H:i:s',
        'meeting_duration' => 'datetime:H:i:s',
        'time_between_meetings' => 'datetime:H:i:s',
        'inscription_end_date' => 'datetime',
        'matching_end_date' => 'datetime',
        'status' => EventStatus::class,
	];

    protected function date(): Attribute {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d')
        );
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    protected function startsAt(): Attribute {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('H:i:s')
        );
    }

    protected function endsAt(): Attribute {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('H:i:s')
        );
    }

    public function meetings() {
        return $this->hasMany(Meeting::class);
    }

    public function files() {
        return $this->hasMany(File::class);
    }

    public function status():Attribute {
		return Attribute::make(
            get: fn () => EventStatus::from($this->attributes['status'])
        );
	}

}

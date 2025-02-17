<?php

namespace App\Models;

use App\Patterns\State\Event\EventStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Event extends Model {
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($event) {
            $event->slug = Str::slug($event->title);
        });
    }

    protected $fillable = [
		'title',
        'slug',
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
        'tables_needed',
        'max_participants',
        'meetings_per_user',
	];

	protected $casts = [
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
            get: fn ($value) => Carbon::parse($value)->format('H:i')
        );
    }

    protected function endsAt(): Attribute {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('H:i')
        );
    }

    public function meetings() {
        return $this->hasMany(Meeting::class);
    }

    public function participants() {
        return $this->hasMany(Registration::class);
    }

    public function files() {
        return $this->hasMany(File::class);
    }

}

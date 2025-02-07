<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use App\Patterns\Role\RequesterRole;
use App\Patterns\State\Event\EventStatus;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory {

	protected $model = Event::class;

	public function definition():array{
		return [
            'title' => $this->faker->text(50),
            'description' => $this->faker->sentence(10),
            'date' => $this->faker->date('d-m-Y'),
            'location' => $this->faker->city,
			'starts_at' => '09:00:00',
            'ends_at' => '17:00:00',
            'inscription_end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'matching_end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'logo_path' => $this->faker->filePath(),
			'responsible_id' => User::whereHas('roles', function($query) {
				$query->where('name', 'responsible');
			})->inRandomOrder()->first('id')->id,
			'status' => EventStatus::Registration,
		];
	}

}

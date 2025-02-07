<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use App\Patterns\Role\RequesterRole;
use App\Patterns\State\Event\EventStatus;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory {

	protected $model = Meeting::class;

	public function definition():array{
		return [
            'title' => $this->faker->text(50),
			'requester_id' => User::inRandomOrder()->first('id')->id,
			'receiver_id' => User::inRandomOrder()->first('id')->id,
            'event_id' => Event::inRandomOrder()->first('id')->id,
            'description' => $this->faker->sentence(10),
            'requester_role' => RequesterRole::Buyer,
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

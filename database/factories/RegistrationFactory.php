<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory {

	protected $model = Meeting::class;

	public function definition():array{
		return [
			'participant_id' => User::whereHas('roles', function($query) {
				$query->where('name', 'participant');
			})->inRandomOrder()->first('id')->id,
            'event_id' => Event::inRandomOrder()->first('id')->id,
            'inscription_date' => now(),
            'interests' => $this->faker->sentence,
            'products_services' => $this->faker->sentence,
            'remaining_meeting' => 5,
		];
	}

}

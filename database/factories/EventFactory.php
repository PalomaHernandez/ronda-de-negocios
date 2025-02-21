<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use App\Patterns\Role\RequesterRole;
use App\Patterns\State\Event\EventStatus;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory {

	protected $model = Event::class;

	public function definition():array{
		$title = $this->faker->text(50);
		return [
            'title' => $title,
			'slug'=> Str::slug($title),
            'description' => $this->faker->sentence(10),
            'date' => $this->faker->date,
            'location' => $this->faker->city,
			'starts_at' => '09:00:00',
            'ends_at' => '17:00:00',
            'inscription_end_date' => $this->faker->date('Y-m-d', '+1 year'),
            'matching_end_date' => $this->faker->date('Y-m-d', '+1 year'),
			'logo_path' => 'images/7d51a789-9411-43ca-88bb-cea9cde45c7d.jpg',
            'logo_url' => 'http://127.0.0.1:8000/storage/images/7d51a789-9411-43ca-88bb-cea9cde45c7d.jpg',
			'responsible_id' => User::whereHas('roles', function($query) {
				$query->where('name', 'responsible');
			})->inRandomOrder()->first('id')->id,
			'status' => EventStatus::Registration,
		];
	}

}

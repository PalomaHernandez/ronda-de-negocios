<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory {

	protected $model = File::class;

	public function definition():array{
		return [
			'path' => fake()->filePath(),
			'event_id' => Event::inRandomOrder()->first('id')->id,
            'original_name' => $this->faker->text(20),
		];
	}

}

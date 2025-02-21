<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory {

	protected $model = File::class;

	public function definition():array{
		return [
			'path' => 'files/7d51a789-9411-43ca-88bb-cea9cde45c7d.jpg',
			'url' => 'http://127.0.0.1:8000/storage/files/7d51a789-9411-43ca-88bb-cea9cde45c7d.jpg',
			'event_id' => Event::inRandomOrder()->first('id')->id,
            'original_name' => $this->faker->text(20),
		];
	}

}

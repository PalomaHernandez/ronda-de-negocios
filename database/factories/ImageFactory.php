<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory {

	protected $model = Image::class;

	public function definition():array{
		return [
			'path' => fake()->filePath(),
			'user_id' => User::inRandomOrder()->first('id')->id,
		];
	}

}

<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory {

	protected $model = Image::class;

	public function definition():array{
		return [
			'path' => 'http://127.0.0.1:8000/storage/images/c75e37c7-bd36-4d9a-819d-d3e6f5edc6ad.png',
			'user_id' => User::inRandomOrder()->first('id')->id,
		];
	}

}

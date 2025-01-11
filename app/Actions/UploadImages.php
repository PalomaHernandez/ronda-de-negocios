<?php

namespace App\Actions;

use App\Models\Registration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class UploadImages{

	public static function validate():void{
		request()->validate([
			'files' => ['array', 'required'],
			'files.*' => ['required','file', 'mimes:jpg,jpeg,png'],
		]);
	}

	public static function execute(Registration $registration):Collection{
		$images = request()->file('files');
        $imageFiles = collect();

        foreach ($images as $image) {
            $extension = $image->getClientOriginalExtension();

            $imageName = uuid_create() . '.' . $extension;

            $imageFiles->add($registration->images()->create([
                'path' => Storage::disk('local')->putFileAs('images', $image, $imageName)
            ]));
        }

        return $imageFiles;
	}

}
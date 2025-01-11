<?php

namespace App\Actions;

use App\Models\Event;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class UploadFiles {

	public static function validate():void{
		request()->validate([
			'files' => ['array', 'required'],
			'files.*' => ['required','file', 'mimes:jpg,jpeg,png,pdf,doc,docx,txt', 'max:8000'],
		]);
	}

	public static function execute(Event $event):Collection{
		$files = request()->file('files');
		$docFiles = collect();
		foreach($files as $file){
            $extension = $file->getClientOriginalExtension(); //Ver si es seguro.
            $fileName = uuid_create() . '.' . $extension;
			$docFiles->add($event->files()->create([
				'path' => Storage::disk('local')->putFileAs('files', $file, $fileName)
			]));
		}
		return $docFiles;
	}

}
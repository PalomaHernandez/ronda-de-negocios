<?php

namespace App\Actions;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class UploadFiles {

	public static function execute(Event $event, string $type = 'documents'):void{
		$files = $type === 'logo'
        ? [request()->file('logo')] 
        : request()->file('documents');

		foreach ($files as $file) {
			$fileOriginalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = uuid_create() . '.' . $extension;

            $path = Storage::disk('public')->putFileAs('files', $file, $fileName);

            if ($type === 'logo') {
                $event->update(['logo_path' => url('storage/' . $path)]);
            } else {
                $event->files()->create(['path' => url('storage/' . $path), 'original_name' => $fileOriginalName]);
            }
        }
    
	}

}
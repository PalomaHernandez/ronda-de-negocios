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
            $extension = $file->getClientOriginalExtension();
            $fileName = uuid_create() . '.' . $extension;

            $path = Storage::disk('public')->putFileAs('files', $file, $fileName);

            if ($type === 'logo') {
                $event->update(['logo_path' => $path]);
            } else {
                $event->files()->create(['path' => $path]);
            }
        }
    
	}

}
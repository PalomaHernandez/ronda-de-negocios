<?php

namespace App\Actions;

use App\Models\Event;
use App\Services\CloudinaryService;

class UploadFiles {

	public static function execute(Event $event, string $type = 'documents'):void{
		$files = $type === 'logo'
        ? [request()->file('logo')] 
        : request()->file('documents');

		foreach ($files as $file) {
			$uploadData = CloudinaryService::upload($file);

            if ($type === 'logo') {
                $event->update(['logo_url' => $uploadData['url'],
                    'logo_public_id' => $uploadData['public_id']]);
            } else {
                $event->files()->create(['url' => $uploadData['url'],
                    'public_id' => $uploadData['public_id'],
                    'original_name' => $file->getClientOriginalName()]);
            }
        }
    
	}

}
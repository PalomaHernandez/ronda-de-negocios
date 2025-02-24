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

            //$path = Storage::disk('public')->putFileAs('files', $file, $fileName);
            // Guardar el archivo directamente en "public/uploads"
            $destinationPath = public_path('uploads');
            $file->move($destinationPath, $fileName);

            // Guardar la URL correctamente
            $url = url('uploads/' . $fileName);
            if ($type === 'logo') {
                $event->update(['logo_path' => $destinationPath, 'logo_url' => $url]);
            } else {
                $event->files()->create(['path' => $destinationPath,'url' => $url, 'original_name' => $fileOriginalName]);
            }
        }
    
	}

}
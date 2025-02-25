<?php
namespace App\Actions;

use App\Models\User;
use App\Services\CloudinaryService;

class UploadImages
{

    public static function execute(User $user, string $type = 'gallery'):void{
        $images = $type === 'logo'
        ? [request()->file('logo')] 
        : request()->file('gallery');

        foreach ($images as $image) {
            $uploadData = CloudinaryService::upload($image);

            if ($type === 'logo') {
                $user->update(['logo_url' => $uploadData['url'],
                    'logo_public_id' => $uploadData['public_id']]);
            } else {
                $user->images()->create(['url' => $uploadData['url'],
                    'public_id' => $uploadData['public_id']]);
            }
        }
    }
}

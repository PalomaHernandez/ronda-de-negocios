<?php
namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class UploadImages
{

    public static function execute(User $user, string $type = 'gallery'):void{
        $images = $type === 'logo'
        ? [request()->file('logo')] 
        : request()->file('gallery');

        foreach ($images as $image) {
            $extension = $image->getClientOriginalExtension();
            $imageName = uuid_create() . '.' . $extension;

            $path = Storage::disk('public')->putFileAs('images', $image, $imageName);

            if ($type === 'logo') {
                $user->update(['logo_path' => url('storage/' . $path)]);
            } else {
                $user->images()->create(['path' => url('storage/' . $path)]);
            }
        }
    }
}

<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService {
    public static function upload(UploadedFile $file): ?array {
        $uploadedFile = Cloudinary::upload($file->getRealPath());
        return [
            'url' => $uploadedFile->getSecurePath(),
            'public_id' => $uploadedFile->getPublicId(),
        ];
    }

    public static function delete(string $publicId): bool {
        $response = Cloudinary::destroy($publicId);
        return $response['result'] === 'ok';
    }
}

<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService {
    public static function upload(UploadedFile $file): ?array {
        $uploadedFile = cloudinary()->upload($file->getRealPath());
        return [
            'url' => $uploadedFile->getSecurePath(),
            'public_id' => $uploadedFile->getPublicId(),
        ];
    }

    public static function delete(string $publicId): bool {
        $response = cloudinary()->destroy($publicId);
        return $response['result'] === 'ok';
    }
}

<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService {
    public static function upload(UploadedFile $file): ?array {
        $uploadedFile = Cloudinary::upload($file->getRealPath());
        Log::info(json_encode($uploadedFile));
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

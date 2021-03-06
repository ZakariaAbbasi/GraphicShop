<?php

namespace App\Utilities;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageUploader
{
    public static function uploaded($image, $path, $diskType = 'local_storage')
    {
        if (!is_null($image))
            Storage::disk($diskType)->put($path, File::get($image));
    }

    public static function uploadMany(array $images, $path, $diskType = 'public_storage')
    {
        $imagePath = [];
        foreach ($images as $key => $image) {

            $fullPath = $path . $key . '_' . $image->getClientOriginalName();

            self::uploaded($image, $fullPath, $diskType);

            $imagePath += [$key => $fullPath];
        }
        return $imagePath;
    }
}

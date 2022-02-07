<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Storage;

class FileRemover
{
    public static function remove($filePath, $diskType = 'public_storage')
    {
        return Storage::disk($diskType)->delete($filePath);
    }
}
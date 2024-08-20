<?php

namespace App\Utils;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UploadFile
{
    public function uploadSingleFile($file, $path): string
    {
        if (is_string($file)) {
            // Create a file instance from the file path
            $file = new UploadedFile($file, basename($file));
        }

        $filename = "uploads/$path".time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path("uploads/$path"), $filename);

        return $filename;
    }

    public function deleteExistFile($path)
    {
        if(File::exists("uploads/$path") ) {
            File::delete("uploads/$path");
        }
    }
}

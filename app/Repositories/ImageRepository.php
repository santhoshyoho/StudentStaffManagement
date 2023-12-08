<?php

namespace App\Repositories;


use Illuminate\Support\Str;

class ImageRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }

    public function uploadImage($file, $directory, $filePath = null)
    {

        if ($filePath) {
            $fullPath = public_path($filePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        $uniqueID = uniqid(); // Generate a unique identifier
        $fileName = hash('sha256', $uniqueID . Str::random(20)) . '.' . $file->getClientOriginalExtension();
        // Move the uploaded file to the specified directory
        $filePath = $file->move(public_path($directory), $fileName);
        $filePath = $directory . '/' . $fileName;
        return $filePath;
    }


}


<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class SecureImageUpload
{
    /**
     * Validation rules for image uploads (max 2MB).
     */
    public static function rules(): array
    {
        return ['image', 'mimes:jpeg,jpg,png,webp', 'max:2048'];
    }

    /**
     * Store an uploaded image using a random filename.
     */
    public static function store(UploadedFile $file, string $directory): string
    {
        $extension = $file->guessExtension() ?: 'jpg';
        $filename = Str::random(40) . '.' . $extension;
        $destination = public_path(trim($directory, '/'));

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);

        return $filename;
    }
}

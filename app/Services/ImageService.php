<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class ImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new GdDriver());
    }

    /**
     * Compress and save an uploaded image.
     *
     * @param  UploadedFile  $file       The uploaded file from the request.
     * @param  string        $directory  The target directory relative to public_path.
     * @param  array         $options    Override default compression options.
     * @return string                    The relative path of the saved image.
     */
    public function compress(UploadedFile $file, string $directory, array $options = []): string
    {
        // Merge with configurable defaults from settings
        $maxWidth = $options['max_width'] ?? (int) \App\Models\Setting::get('image_max_width', 1920);
        $maxHeight = $options['max_height'] ?? (int) \App\Models\Setting::get('image_max_height', 1080);
        $quality = $options['quality'] ?? (int) \App\Models\Setting::get('image_quality', 80);
        $format = $options['format'] ?? \App\Models\Setting::get('image_format', 'webp');

        // Clamp quality between 10 and 100
        $quality = max(10, min(100, $quality));

        // Read the image
        $image = $this->manager->read($file->getPathname());

        // Scale down if larger than max dimensions (maintains aspect ratio)
        $image->scaleDown(width: $maxWidth, height: $maxHeight);

        // Encode to the target format
        $encoded = match ($format) {
            'webp' => $image->toWebp($quality),
            'png' => $image->toPng(),
            default => $image->toJpeg($quality),
        };

        // Generate unique filename
        $extension = $format === 'jpg' ? 'jpg' : $format;
        $filename = time() . '_' . Str::random(16) . '.' . $extension;

        // Ensure the target directory exists
        $absoluteDir = public_path($directory);
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755, true);
        }

        // Save to disk
        $absolutePath = $absoluteDir . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($absolutePath, (string) $encoded);

        // Return the relative path for database storage
        return $directory . '/' . $filename;
    }

    /**
     * Get the original file size in KB for logging purposes.
     */
    public function getFileSizeKB(UploadedFile $file): float
    {
        return round($file->getSize() / 1024, 2);
    }
}

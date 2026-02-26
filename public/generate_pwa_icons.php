<?php
/**
 * Generate PWA icons from the source icon.
 * Run: php generate_pwa_icons.php
 */

$sourceIcon = __DIR__ . '/img/icon.png';
$outputDir = __DIR__ . '/img/icons';

$sizes = [72, 96, 128, 144, 152, 192, 384, 512];

if (!file_exists($sourceIcon)) {
    die("Source icon not found: $sourceIcon\n");
}

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
    echo "Created directory: $outputDir\n";
}

$sourceImage = imagecreatefrompng($sourceIcon);
if (!$sourceImage) {
    die("Failed to load source image\n");
}

$srcWidth = imagesx($sourceImage);
$srcHeight = imagesy($sourceImage);

echo "Source icon: {$srcWidth}x{$srcHeight}\n";

foreach ($sizes as $size) {
    $destImage = imagecreatetruecolor($size, $size);

    // Preserve transparency
    imagealphablending($destImage, false);
    imagesavealpha($destImage, true);
    $transparent = imagecolorallocatealpha($destImage, 0, 0, 0, 127);
    imagefilledrectangle($destImage, 0, 0, $size, $size, $transparent);

    // Resize with high quality
    imagecopyresampled(
        $destImage,
        $sourceImage,
        0,
        0,
        0,
        0,
        $size,
        $size,
        $srcWidth,
        $srcHeight
    );

    $outputPath = "$outputDir/icon-{$size}x{$size}.png";
    imagepng($destImage, $outputPath, 9);
    imagedestroy($destImage);

    echo "Generated: icon-{$size}x{$size}.png\n";
}

// Generate maskable icons (with padding for safe zone)
$maskableSizes = [192, 512];
foreach ($maskableSizes as $size) {
    $destImage = imagecreatetruecolor($size, $size);

    // White background for maskable
    $white = imagecolorallocate($destImage, 253, 248, 246); // #fdf8f6
    imagefilledrectangle($destImage, 0, 0, $size, $size, $white);

    // Add 10% padding for safe zone
    $padding = (int) ($size * 0.1);
    $innerSize = $size - ($padding * 2);

    imagecopyresampled(
        $destImage,
        $sourceImage,
        $padding,
        $padding,
        0,
        0,
        $innerSize,
        $innerSize,
        $srcWidth,
        $srcHeight
    );

    $outputPath = "$outputDir/icon-maskable-{$size}x{$size}.png";
    imagepng($destImage, $outputPath, 9);
    imagedestroy($destImage);

    echo "Generated: icon-maskable-{$size}x{$size}.png\n";
}

imagedestroy($sourceImage);
echo "\nAll PWA icons generated successfully!\n";

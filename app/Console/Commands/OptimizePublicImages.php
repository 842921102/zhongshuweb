<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class OptimizePublicImages extends Command
{
    protected $signature = 'images:optimize
                            {--path=public/home-assets : Directory to scan}
                            {--max-width=1920 : Max width for JPEG/WebP output}
                            {--quality=82 : JPEG/WebP quality (1-100)}
                            {--min-kb=180 : Only process files larger than this size in KB}';

    protected $description = 'Compress large raster images under public/ for faster page loads';

    public function handle(): int
    {
        if (! extension_loaded('gd')) {
            $this->error('GD extension is required.');

            return self::FAILURE;
        }

        $root = base_path((string) $this->option('path'));
        if (! is_dir($root)) {
            $this->error("Directory not found: {$root}");

            return self::FAILURE;
        }

        $maxWidth = max(320, (int) $this->option('max-width'));
        $quality = max(40, min(95, (int) $this->option('quality')));
        $minBytes = max(1, (int) $this->option('min-kb')) * 1024;

        $optimized = 0;
        $savedBytes = 0;

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $path = $file->getPathname();
            $ext = strtolower($file->getExtension());
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }

            $before = $file->getSize();
            if ($before < $minBytes) {
                continue;
            }

            $result = $this->optimizeFile($path, $ext, $maxWidth, $quality);
            if ($result === null) {
                continue;
            }

            clearstatcache(true, $path);
            $after = filesize($path) ?: $before;
            if ($after < $before) {
                $optimized++;
                $savedBytes += ($before - $after);
                $this->line(sprintf(
                    'OK %s  %.1fMB → %.1fMB',
                    str_replace(base_path().'/', '', $path),
                    $before / 1024 / 1024,
                    $after / 1024 / 1024,
                ));
            }
        }

        $this->info("Optimized {$optimized} files, saved ".number_format($savedBytes / 1024 / 1024, 2).' MB.');

        return self::SUCCESS;
    }

    private function optimizeFile(string $path, string $ext, int $maxWidth, int $quality): ?bool
    {
        $image = match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };

        if (! $image) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        if ($width <= 0 || $height <= 0) {
            imagedestroy($image);

            return null;
        }

        if ($width > $maxWidth) {
            $newHeight = (int) round($height * ($maxWidth / $width));
            $resized = imagecreatetruecolor($maxWidth, $newHeight);
            if ($ext === 'png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        $ok = match ($ext) {
            'jpg', 'jpeg' => imagejpeg($image, $path, $quality),
            'png' => imagepng($image, $path, 6),
            'webp' => function_exists('imagewebp') ? imagewebp($image, $path, $quality) : false,
            default => false,
        };

        imagedestroy($image);

        return $ok ? true : null;
    }
}

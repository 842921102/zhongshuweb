<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class FileSize
{
    public static function labelForStoragePath(?string $path, ?string $disk = null): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return null;
        }

        $disk ??= upload_disk();

        if (! Storage::disk($disk)->exists($path)) {
            return null;
        }

        return self::formatBytes(Storage::disk($disk)->size($path));
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1).'MB';
        }

        if ($bytes >= 1024) {
            return max(1, (int) round($bytes / 1024)).'KB';
        }

        return $bytes.'B';
    }
}

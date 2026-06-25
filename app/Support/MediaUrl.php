<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Throwable;

class MediaUrl
{
    public static function resolve(mixed $path, ?string $fallback = null): ?string
    {
        $path = self::normalizeStoredPath($path);

        if ($path === null) {
            return $fallback !== null ? self::resolve($fallback) : null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '/')) {
            return $path;
        }

        if ($publicUrl = self::publicAssetUrl($path)) {
            return $publicUrl;
        }

        return self::storagePublicUrl($path) ?? '/storage/'.ltrim($path, '/');
    }

    /** Paths served from public/ (not storage/app/public). */
    public static function publicAssetUrl(string $path): ?string
    {
        $relative = ltrim($path, '/');
        $prefixes = ['home-assets/', 'css/', 'js/', 'images/', 'data/'];

        foreach ($prefixes as $prefix) {
            if (! str_starts_with($relative, $prefix)) {
                continue;
            }

            if (is_file(public_path($relative))) {
                return '/'.$relative;
            }
        }

        return null;
    }

    public static function storagePublicUrl(string $path): ?string
    {
        foreach (self::storageDisksForPath($path) as $disk) {
            try {
                if (Storage::disk($disk)->exists($path)) {
                    return Storage::disk($disk)->url($path);
                }
            } catch (Throwable) {
                continue;
            }
        }

        return null;
    }

    /** @return list<string> */
    public static function storageDisksForPath(string $path): array
    {
        $uploadDisk = upload_disk();
        $disks = [$uploadDisk];

        if ($uploadDisk !== 'public') {
            $disks[] = 'public';
        }

        return array_values(array_unique($disks));
    }

    public static function resolveStorageDisk(string $path): ?string
    {
        foreach (self::storageDisksForPath($path) as $disk) {
            try {
                if (Storage::disk($disk)->exists($path)) {
                    return $disk;
                }
            } catch (Throwable) {
                continue;
            }
        }

        return null;
    }

    public static function normalizeStoredPath(mixed $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (is_string($path)) {
            $path = trim($path);

            return $path !== '' ? $path : null;
        }

        if (is_array($path)) {
            foreach (['path', 'url', 'src', 'image', 'image_pc'] as $key) {
                if (isset($path[$key])) {
                    return self::normalizeStoredPath($path[$key]);
                }
            }

            foreach ($path as $value) {
                $normalized = self::normalizeStoredPath($value);
                if ($normalized !== null) {
                    return $normalized;
                }
            }
        }

        return null;
    }
}

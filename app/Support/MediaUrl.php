<?php

namespace App\Support;

class MediaUrl
{
    public static function resolve(mixed $path, ?string $fallback = null): ?string
    {
        $path = self::normalizeStoredPath($path);

        if ($path === null) {
            return $fallback;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '/')) {
            return $path;
        }

        return '/storage/'.ltrim($path, '/');
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

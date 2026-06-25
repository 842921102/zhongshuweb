<?php

namespace App\Support;

class OverlayCopyStyle
{
    public static function inline(?string $titleColor, ?string $subtitleColor): string
    {
        $parts = [];

        if ($normalized = self::normalizeColor($titleColor)) {
            $parts[] = '--overlay-title-color: '.$normalized;
        }

        if ($normalized = self::normalizeColor($subtitleColor)) {
            $parts[] = '--overlay-subtitle-color: '.$normalized;
        }

        return $parts === [] ? '' : implode('; ', $parts).';';
    }

    public static function normalizeColor(?string $color): ?string
    {
        if (blank($color)) {
            return null;
        }

        $color = trim($color);

        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $color) === 1) {
            return strtolower($color);
        }

        return null;
    }
}

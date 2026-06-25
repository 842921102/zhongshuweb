<?php

namespace App\Support;

class SiteLayoutCache
{
    public static function key(string $locale): string
    {
        return "site_layout_v1_{$locale}";
    }

    public static function forget(?string $locale = null): void
    {
        \App\Services\SiteLayoutService::clearRequestCache($locale);

        // Layout data is kept in request memory only (no serialized Eloquent in file cache).
    }
}

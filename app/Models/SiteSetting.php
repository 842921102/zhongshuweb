<?php

namespace App\Models;

use App\Support\SiteLayoutCache;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value', 'group', 'label', 'type'])]
class SiteSetting extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_settings'));
        static::deleted(fn () => Cache::forget('site_settings'));
        static::saved(fn () => SiteLayoutCache::forget());
        static::deleted(fn () => SiteLayoutCache::forget());
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever('site_settings', function () {
            return static::query()->pluck('value', 'key')->all();
        });

        return $settings[$key] ?? $default;
    }
}

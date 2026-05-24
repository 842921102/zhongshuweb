<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Str;

class ReleaseInfo
{
    public static function version(): string
    {
        return (string) config('release.version', '0.0.0-dev');
    }

    public static function label(): string
    {
        return trim((string) config('release.label', ''));
    }

    public static function environment(): string
    {
        return (string) config('app.env', 'production');
    }

    public static function environmentLabel(): string
    {
        return match (app()->environment()) {
            'production' => '正式环境',
            'staging' => '预发布环境',
            'local' => '本地开发',
            default => Str::headline(static::environment()),
        };
    }

    public static function publishedAt(): ?Carbon
    {
        $raw = config('release.published_at');

        if (blank($raw)) {
            return null;
        }

        try {
            return Carbon::parse($raw)->timezone(config('app.timezone'));
        } catch (\Throwable) {
            return null;
        }
    }

    public static function publishedAtDisplay(): ?string
    {
        return static::publishedAt()?->format('Y-m-d H:i');
    }

    /** @return list<array{key: string, value: string}> */
    public static function items(): array
    {
        $items = [
            ['key' => '系统版本', 'value' => static::version()],
            ['key' => '运行环境', 'value' => static::environmentLabel()],
        ];

        if ($label = static::label()) {
            $items[] = ['key' => '本次更新', 'value' => $label];
        }

        if ($at = static::publishedAtDisplay()) {
            $items[] = ['key' => '发布时间', 'value' => $at];
        }

        return $items;
    }

    public static function environmentClass(): string
    {
        return match (app()->environment()) {
            'production' => 'is-production',
            'staging' => 'is-staging',
            'local' => 'is-local',
            default => 'is-other',
        };
    }
}

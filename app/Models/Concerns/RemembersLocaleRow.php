<?php

namespace App\Models\Concerns;

trait RemembersLocaleRow
{
    /** @var array<string, static> */
    private static array $rememberedLocaleRows = [];

    public static function forLocale(string $locale = 'zh-cn'): static
    {
        if (isset(static::$rememberedLocaleRows[$locale])) {
            return static::$rememberedLocaleRows[$locale];
        }

        return static::$rememberedLocaleRows[$locale] = static::query()->firstOrCreate(
            ['locale' => $locale],
            static::defaultAttributesForLocale($locale)
        );
    }

    protected static function flushLocaleCache(?string $locale): void
    {
        if ($locale !== null) {
            unset(static::$rememberedLocaleRows[$locale]);
        }
    }

    protected static function defaultAttributesForLocale(string $locale): array
    {
        if (! method_exists(static::class, 'defaultAttributes')) {
            return [];
        }

        $method = new \ReflectionMethod(static::class, 'defaultAttributes');

        return $method->getNumberOfParameters() > 0
            ? static::defaultAttributes($locale)
            : static::defaultAttributes();
    }

    protected static function bootRemembersLocaleRow(): void
    {
        static::saved(function (self $model): void {
            static::flushLocaleCache($model->locale);
        });

        static::deleted(function (self $model): void {
            static::flushLocaleCache($model->locale);
        });
    }
}

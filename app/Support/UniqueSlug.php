<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class UniqueSlug
{
    /**
     * 从标题/名称生成唯一 slug；纯中文等无法拉丁化时使用 {prefix}-{id|random}。
     */
    public static function for(Model $model, string $source, string $prefix): string
    {
        $base = Str::slug($source);

        if ($base === '') {
            $id = $model->getKey();

            return $prefix.'-'.($id ?: Str::lower(Str::random(8)));
        }

        $slug = $base;
        $n = 0;

        while (static::taken($model, $slug)) {
            $slug = $base.'-'.(++$n);
        }

        return $slug;
    }

    private static function taken(Model $model, string $slug): bool
    {
        $query = $model->newQuery()->where('slug', $slug);

        if ($model->getKey()) {
            $query->whereKeyNot($model->getKey());
        }

        return $query->exists();
    }
}

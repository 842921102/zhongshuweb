<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'title', 'slug', 'page_key', 'subtitle', 'excerpt', 'content', 'cover_image',
    'button_text', 'button_url', 'seo_title', 'seo_description', 'sort_order',
    'is_published', 'locale', 'published_at',
])]
class Page extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Page $page): void {
            if (blank($page->slug) && filled($page->title)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public static function byKey(string $key, string $locale = 'zh-cn'): ?self
    {
        return static::query()
            ->forLocale($locale)
            ->published()
            ->where('page_key', $key)
            ->first();
    }
}

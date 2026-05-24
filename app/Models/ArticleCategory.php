<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use App\Support\UniqueSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name', 'slug', 'description', 'sort_order', 'is_active', 'locale',
])]
class ArticleCategory extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (ArticleCategory $category): void {
            if (blank($category->slug) && filled($category->name)) {
                $category->slug = UniqueSlug::for($category, $category->name, 'news-cat');
            }
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function ensureDefaults(string $locale = 'zh-cn'): void
    {
        $categories = [
            ['name' => '展会活动', 'slug' => 'exhibition', 'description' => '展会、博览会等活动资讯'],
            ['name' => '公司动态', 'slug' => 'company', 'description' => '企业新闻与公司介绍'],
            ['name' => '行业资讯', 'slug' => 'industry', 'description' => '行业趋势与深度报道'],
            ['name' => '产品资讯', 'slug' => 'product', 'description' => '产品发布与应用案例'],
        ];

        foreach ($categories as $i => $row) {
            static::query()->updateOrCreate(
                ['slug' => $row['slug'], 'locale' => $locale],
                array_merge($row, [
                    'sort_order' => $i,
                    'is_active' => true,
                    'locale' => $locale,
                ])
            );
        }
    }
}

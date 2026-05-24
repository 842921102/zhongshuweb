<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use App\Support\UniqueSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'category_id', 'title', 'slug', 'summary', 'content', 'cover_image',
    'author', 'seo_title', 'seo_description', 'is_published', 'is_featured',
    'is_home_show', 'sort_order', 'locale', 'views', 'published_at',
])]
class Article extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'is_home_show' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Article $article): void {
            if (blank($article->slug) && filled($article->title)) {
                $article->slug = UniqueSlug::for($article, $article->title, 'article');
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $q): void {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeHome(Builder $query): Builder
    {
        return $query->where('is_home_show', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function url(): string
    {
        return localized_route('news.show', ['article' => $this->slug ?: $this->id], $this->locale);
    }

    public function badgeLabel(): ?string
    {
        return $this->category?->name;
    }

    public function displayDate(): string
    {
        return $this->published_at?->format('Y-m-d') ?? $this->created_at->format('Y-m-d');
    }

    public function listSummary(int $limit = 120): string
    {
        $text = $this->summary ?: strip_tags((string) $this->content);

        return str($text)->limit($limit)->toString();
    }
}

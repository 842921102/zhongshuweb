<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'title', 'slug', 'category_id', 'region', 'scene_type', 'summary', 'excerpt',
    'cover_image', 'cover_image_mobile', 'content', 'product_tags', 'detail_url', 'sort_order',
    'is_home_show', 'is_featured', 'is_active', 'locale',
    'published_at', 'meta_title', 'meta_description',
])]
class CaseStudy extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_home_show' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'product_tags' => 'array',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (CaseStudy $case): void {
            if (blank($case->slug) && filled($case->title)) {
                $slug = Str::slug($case->title);
                $case->slug = $slug !== '' ? $slug : 'case-'.Str::lower(Str::random(10));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CaseStudyCategory::class, 'category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function (Builder $q): void {
            $q->whereNull('published_at')
                ->orWhere('published_at', '<=', now());
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

    public function sceneLabel(): string
    {
        return $this->category?->name ?? $this->scene_type ?? '';
    }

    public function listExcerpt(): string
    {
        return trim((string) ($this->excerpt ?: $this->summary ?: ''));
    }

    public function url(): string
    {
        if (filled($this->slug)) {
            return localized_url('/cases/'.$this->slug, $this->locale);
        }

        return filled($this->detail_url) ? localized_url($this->detail_url, $this->locale) : '#';
    }

    /** @return list<string> */
    public function tagList(): array
    {
        $tags = $this->product_tags;

        if (! is_array($tags)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', $tags)));
    }
}

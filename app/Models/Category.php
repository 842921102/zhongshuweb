<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use App\Support\SiteLayoutCache;
use App\Models\Concerns\HasOverlayCopyColors;
use App\Support\UniqueSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'parent_id', 'name', 'slug', 'subtitle', 'description', 'icon', 'cover_image', 'cover_image_mobile',
    'link', 'overlay_title_color', 'overlay_subtitle_color', 'sort_order', 'is_active', 'is_home_show', 'is_home_featured',
    'is_station_tab', 'show_in_catalog', 'locale',
])]
class Category extends Model
{
    use HasLocale;
    use HasOverlayCopyColors;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_home_show' => 'boolean',
            'is_home_featured' => 'boolean',
            'is_station_tab' => 'boolean',
            'show_in_catalog' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Category $category): void {
            if (blank($category->slug) && filled($category->name)) {
                $category->slug = UniqueSlug::for($category, $category->name, 'category');
            }
        });

        static::saved(fn () => SiteLayoutCache::forget());
        static::deleted(fn () => SiteLayoutCache::forget());
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('sort_order');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeHomeRoots(Builder $query): Builder
    {
        return $query->roots()->where('is_home_show', true);
    }

    public function scopeStationTabs(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id')->where('is_station_tab', true);
    }

    public function scopeCatalogTabs(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id')->where('show_in_catalog', true);
    }

    public function scopeHierarchicalOrder(Builder $query): Builder
    {
        return $query
            ->orderByRaw('COALESCE(parent_id, id)')
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function domKey(): string
    {
        return 'category-'.$this->id;
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    public function coverPcPath(): ?string
    {
        return $this->cover_image ?: $this->icon;
    }

    public function coverMobilePath(): ?string
    {
        return $this->cover_image_mobile;
    }
}

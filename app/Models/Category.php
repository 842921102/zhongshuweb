<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use App\Support\UniqueSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'parent_id', 'name', 'slug', 'subtitle', 'description', 'icon', 'cover_image',
    'link', 'sort_order', 'is_active', 'is_home_show', 'is_home_featured',
    'is_station_tab', 'locale',
])]
class Category extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_home_show' => 'boolean',
            'is_home_featured' => 'boolean',
            'is_station_tab' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Category $category): void {
            if (blank($category->slug) && filled($category->name)) {
                $category->slug = UniqueSlug::for($category, $category->name, 'category');
            }
        });
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

    public function domKey(): string
    {
        return 'category-'.$this->id;
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }
}

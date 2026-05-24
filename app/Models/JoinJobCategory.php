<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use App\Support\UniqueSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'sort_order', 'is_active', 'locale'])]
class JoinJobCategory extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saving(function (JoinJobCategory $category): void {
            if (blank($category->slug) && filled($category->name)) {
                $category->slug = UniqueSlug::for($category, $category->name, 'join-cat');
            }
        });
    }

    public function positions(): HasMany
    {
        return $this->hasMany(JoinPosition::class, 'category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}

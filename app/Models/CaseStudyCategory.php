<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'sort_order', 'is_active', 'locale'])]
class CaseStudyCategory extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function cases(): HasMany
    {
        return $this->hasMany(CaseStudy::class, 'category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function ensureDefaults(string $locale = 'zh-cn'): void
    {
        $categories = [
            ['name' => '产业基地', 'slug' => 'industry-base'],
            ['name' => '园区', 'slug' => 'campus'],
            ['name' => '市政环卫', 'slug' => 'municipal'],
            ['name' => '商业地产', 'slug' => 'commercial'],
            ['name' => '工业园区', 'slug' => 'industrial-park'],
        ];

        foreach ($categories as $i => $row) {
            static::query()->updateOrCreate(
                ['slug' => $row['slug'], 'locale' => $locale],
                array_merge($row, ['sort_order' => $i, 'is_active' => true, 'locale' => $locale])
            );
        }
    }
}

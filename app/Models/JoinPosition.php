<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'category_id', 'title', 'department_label', 'location', 'employment_type',
    'experience', 'summary', 'tags', 'sort_order', 'is_active', 'locale',
])]
class JoinPosition extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JoinJobCategory::class, 'category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** @return list<string> */
    public function tagList(): array
    {
        $tags = $this->tags;

        return is_array($tags) ? array_values(array_filter($tags, fn ($t) => filled($t))) : [];
    }

    /** @return list<string> */
    public function metaItems(): array
    {
        return array_values(array_filter([
            $this->department_label ?: $this->category?->name,
            $this->location,
            $this->employment_type,
            $this->experience,
        ]));
    }
}

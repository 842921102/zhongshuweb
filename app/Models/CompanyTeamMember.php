<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name', 'role', 'bio', 'photo', 'is_featured', 'sort_order', 'is_active', 'locale',
])]
class CompanyTeamMember extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}

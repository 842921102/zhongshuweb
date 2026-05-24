<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name', 'logo', 'link', 'sort_order', 'is_home_show', 'is_active', 'locale',
])]
class SitePartner extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_home_show' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeHome(Builder $query): Builder
    {
        return $query->where('is_home_show', true);
    }
}

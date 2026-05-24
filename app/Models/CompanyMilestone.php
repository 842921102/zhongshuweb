<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'year', 'month_label', 'title', 'image', 'sort_order', 'is_active', 'locale',
])]
class CompanyMilestone extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

}

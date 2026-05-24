<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasLocale
{
    public function scopeForLocale(Builder $query, string $locale = 'zh-cn'): Builder
    {
        return $query->where($this->getTable().'.locale', $locale);
    }
}

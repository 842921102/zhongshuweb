<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use App\Support\SiteLayoutCache;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'group_key', 'group_label', 'label', 'url', 'sort_order', 'is_active', 'locale',
])]
class SiteFooterLink extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => SiteLayoutCache::forget());
        static::deleted(fn () => SiteLayoutCache::forget());
    }
}

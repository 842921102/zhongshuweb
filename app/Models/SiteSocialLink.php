<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'icon',
    'type',
    'url',
    'qr_image',
    'sort_order',
    'is_active',
    'locale',
])]
class SiteSocialLink extends Model
{
    use HasLocale;

    public const TYPE_LINK = 'link';

    public const TYPE_QR = 'qr';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @return array<string, mixed>
     */
    public function toFooterArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'icon' => $this->icon,
            'url' => $this->url,
            'qr_image' => $this->qr_image,
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => \App\Support\SiteLayoutCache::forget());
        static::deleted(fn () => \App\Support\SiteLayoutCache::forget());
    }
}

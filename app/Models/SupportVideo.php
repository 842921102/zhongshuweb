<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'title', 'cover_image', 'video_url', 'duration_label', 'tag',
    'play_count', 'sort_order', 'is_active',
])]
class SupportVideo extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'play_count' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function streamUrl(): string
    {
        return media_url($this->video_url) ?? '#';
    }
}

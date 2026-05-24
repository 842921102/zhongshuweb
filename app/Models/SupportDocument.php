<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'title', 'category', 'version', 'published_label', 'page_count',
    'file_path', 'file_size_label', 'sort_order', 'is_active',
])]
class SupportDocument extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'page_count' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function downloadUrl(): string
    {
        return media_url($this->file_path) ?? '#';
    }
}

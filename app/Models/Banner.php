<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use App\Support\MediaUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'subtitle',
    'media_type',
    'image',
    'image_mobile',
    'video',
    'video_mobile',
    'link',
    'button_text',
    'position',
    'locale',
    'sort_order',
    'is_active',
    'starts_at',
    'ends_at',
])]
class Banner extends Model
{
    use HasLocale;

    public const TYPE_IMAGE = 'image';

    public const TYPE_VIDEO = 'video';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function isVideo(): bool
    {
        return $this->media_type === self::TYPE_VIDEO;
    }

    public function isImage(): bool
    {
        return ! $this->isVideo();
    }

    public function posterPcUrl(): ?string
    {
        return MediaUrl::resolve($this->image);
    }

    public function posterMobileUrl(): ?string
    {
        return MediaUrl::resolve($this->image_mobile, $this->posterPcUrl());
    }

    public function videoPcUrl(): ?string
    {
        return MediaUrl::resolve($this->video);
    }

    public function videoMobileUrl(): ?string
    {
        return MediaUrl::resolve($this->video_mobile, $this->videoPcUrl());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $q): void {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $q): void {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeHome(Builder $query): Builder
    {
        return $query->where('position', 'home');
    }
}

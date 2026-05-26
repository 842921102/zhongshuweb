<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'category_id', 'name', 'slug', 'model_no', 'subtitle', 'summary', 'meta_title', 'meta_description', 'metrics',
    'cover_image', 'cover_image_mobile', 'home_image', 'home_image_mobile', 'hero_video', 'hero_poster',
    'showcase_images', 'detail_hero_image', 'detail_hero_image_mobile', 'detail_gallery', 'detail_features',
    'spec_groups', 'spec_document', 'rights_content', 'contact_bg_image', 'contact_bg_image_mobile',
    'detail_url', 'sort_order',
    'is_home_show', 'is_home_featured', 'is_active', 'locale',
])]
class Product extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'metrics' => 'array',
            'showcase_images' => 'array',
            'detail_gallery' => 'array',
            'detail_features' => 'array',
            'spec_groups' => 'array',
            'rights_content' => 'array',
            'is_home_show' => 'boolean',
            'is_home_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if (blank($product->slug) && filled($product->name)) {
                $base = Str::slug($product->name);
                $suffix = $product->id ?: Str::lower(Str::random(6));
                $product->slug = $base !== '' ? $base.'-'.$suffix : 'product-'.$suffix;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeHome(Builder $query): Builder
    {
        return $query->where('is_home_show', true);
    }

    public function displayImage(): ?string
    {
        return $this->home_image ?: $this->cover_image;
    }

    public function displayImageMobile(): ?string
    {
        return $this->home_image_mobile ?: $this->cover_image_mobile;
    }

    public function url(): string
    {
        if (filled($this->detail_url) && $this->detail_url !== '#') {
            return str_starts_with($this->detail_url, 'http') || str_starts_with($this->detail_url, '/')
                ? (str_starts_with($this->detail_url, '/') ? localized_url($this->detail_url, $this->locale) : $this->detail_url)
                : localized_url('/'.$this->detail_url, $this->locale);
        }

        return localized_route('products.show', ['product' => $this->slug ?: $this->id], $this->locale);
    }

    /** @return list<array{0: string, 1: string}> */
    public function metricPairs(): array
    {
        $metrics = $this->metrics;

        if (! is_array($metrics)) {
            return [];
        }

        $pairs = [];

        foreach ($metrics as $row) {
            if (is_array($row) && isset($row['value'], $row['label'])) {
                $pairs[] = [(string) $row['value'], (string) $row['label']];
            } elseif (is_array($row) && count($row) >= 2) {
                $pairs[] = [(string) $row[0], (string) $row[1]];
            }
        }

        return $pairs;
    }

    public function seriesLabel(): string
    {
        return $this->category?->subtitle
            ?: $this->category?->name
            ?: '';
    }

    public function categoryPillLabel(): string
    {
        return $this->category?->name ?: '';
    }

    public function heroImage(): ?string
    {
        return $this->hero_poster ?: $this->home_image ?: $this->cover_image;
    }

    /** @return list<string> */
    public function showcaseImageList(): array
    {
        $images = collect($this->showcase_images ?? [])
            ->filter(fn ($path) => filled($path))
            ->values()
            ->all();

        if ($images !== []) {
            return $images;
        }

        return array_values(array_unique(array_filter([
            $this->home_image,
            $this->cover_image,
        ])));
    }

    /** @return list<string> */
    public function detailGalleryList(): array
    {
        $gallery = collect($this->detail_gallery ?? [])
            ->filter(fn ($path) => filled($path))
            ->values()
            ->all();

        return $gallery;
    }

    /** @return list<string> */
    public function detailFeatureList(): array
    {
        $features = $this->detail_features ?? [];

        return collect($features)
            ->map(function ($row) {
                if (is_string($row)) {
                    return trim($row);
                }
                if (is_array($row) && filled($row['text'] ?? null)) {
                    return trim((string) $row['text']);
                }

                return '';
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return list<array{key: string, label: string, rows: list<array{label: string, value: string}>}>
     */
    public function specGroupList(): array
    {
        $groups = $this->spec_groups ?? [];

        if (is_array($groups) && $groups !== []) {
            return collect($groups)->map(function ($group, $index) {
                $label = (string) ($group['label'] ?? '参数');
                $key = (string) ($group['key'] ?? 'spec-'.$index);
                $rows = collect($group['rows'] ?? [])->map(function ($row) {
                    if (! is_array($row)) {
                        return null;
                    }

                    $rowLabel = (string) ($row['label'] ?? '');
                    $value = (string) ($row['value'] ?? '');

                    if ($rowLabel === '' && $value === '') {
                        return null;
                    }

                    return ['label' => $rowLabel, 'value' => $value];
                })->filter()->values()->all();

                return ['key' => $key, 'label' => $label, 'rows' => $rows];
            })->filter(fn ($g) => $g['rows'] !== [])->values()->all();
        }

        $pairs = $this->metricPairs();
        if ($pairs === []) {
            return [];
        }

        return [[
            'key' => 'spec-default',
            'label' => '基本参数',
            'rows' => collect($pairs)->map(fn ($pair) => [
                'label' => $pair[1],
                'value' => $pair[0],
            ])->all(),
        ]];
    }

    public function specDocumentUrl(): ?string
    {
        return filled($this->spec_document) ? media_url($this->spec_document) : null;
    }
}

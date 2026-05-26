<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use App\Support\IndustrySolutionDetailData;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'title', 'slug', 'summary', 'excerpt',
    'cover_image', 'cover_image_mobile', 'content', 'detail_data',
    'detail_button_text', 'external_url', 'sort_order',
    'is_active', 'locale', 'published_at',
    'meta_title', 'meta_description',
])]
class IndustrySolution extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'published_at' => 'datetime',
            'detail_data' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (IndustrySolution $record): void {
            if (blank($record->slug) && filled($record->title)) {
                $slug = Str::slug($record->title);
                $record->slug = $slug !== '' ? $slug : 'industry-'.Str::lower(Str::random(10));
            }

            if (is_array($record->detail_data)) {
                $existing = $record->exists ? $record->getOriginal('detail_data') : null;
                $record->detail_data = IndustrySolutionDetailData::normalizeForSave(
                    $record->detail_data,
                    is_array($existing) ? $existing : null,
                );
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function (Builder $q): void {
            $q->whereNull('published_at')
                ->orWhere('published_at', '<=', now());
        });
    }

    public function cardSummary(): string
    {
        return trim((string) ($this->excerpt ?: $this->summary ?: ''));
    }

    public function heroDescription(): string
    {
        return trim((string) ($this->summary ?: $this->excerpt ?: ''));
    }

    public function url(): string
    {
        if (filled($this->slug)) {
            return localized_url('/industry-cases/'.$this->slug, $this->locale);
        }

        return filled($this->external_url)
            ? localized_url($this->external_url, $this->locale)
            : '#';
    }

    public function anchorId(): string
    {
        return 'industry-'.$this->slug;
    }

    /**
     * @return array<string, mixed>
     */
    public function detail(): array
    {
        $stored = is_array($this->detail_data) ? $this->detail_data : [];

        return array_replace_recursive($this->defaultDetailStructure(), $stored);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultDetailStructure(): array
    {
        return [
            'hero' => [
                'slides' => [],
                'image_pc' => $this->cover_image,
                'image_mobile' => $this->cover_image_mobile,
                'video_url' => null,
            ],
            'stats' => [
                'title' => '',
                'footnote' => '',
                'items' => [],
            ],
            'coverage' => [
                'title' => '',
                'subtitle' => '',
                'image_pc' => null,
                'image_mobile' => null,
            ],
            'scenes' => [],
        ];
    }

    /**
     * @return list<array{image_pc: ?string, image_mobile: ?string, video_url: ?string}>
     */
    public function heroSlides(): array
    {
        $hero = $this->detail()['hero'] ?? [];
        $slides = $hero['slides'] ?? [];

        if (is_array($slides) && $slides !== []) {
            $normalized = [];
            foreach ($slides as $slide) {
                if (! is_array($slide)) {
                    continue;
                }
                $pc = $slide['image_pc'] ?? $slide['image'] ?? null;
                if (blank($pc) && blank($slide['video_url'] ?? null)) {
                    continue;
                }
                $normalized[] = [
                    'image_pc' => $pc,
                    'image_mobile' => $slide['image_mobile'] ?? null,
                    'video_url' => $slide['video_url'] ?? null,
                ];
            }

            if ($normalized !== []) {
                return $normalized;
            }
        }

        $pc = $hero['image_pc'] ?? $this->cover_image;
        if (blank($pc) && blank($hero['video_url'] ?? null)) {
            return [];
        }

        return [[
            'image_pc' => $pc,
            'image_mobile' => $hero['image_mobile'] ?? $this->cover_image_mobile,
            'video_url' => $hero['video_url'] ?? null,
        ]];
    }

    public function productLinkUrl(?string $url): string
    {
        if (blank($url)) {
            return '#';
        }

        $url = trim($url);
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return localized_url($url, $this->locale);
    }

    /**
     * @return list<array{id: string, label: string}>
     */
    public function detailNavSections(): array
    {
        $detail = $this->detail();
        $sections = [];

        $stats = $detail['stats'] ?? [];
        if (filled($stats['title'] ?? null) || ! empty($stats['items'])) {
            $sections[] = ['id' => 'ic-section-stats', 'label' => '解决方案价值'];
        }

        $coverage = $detail['coverage'] ?? [];
        if (filled($coverage['title'] ?? null) || filled($coverage['subtitle'] ?? null) || filled($coverage['image_pc'] ?? null)) {
            $sections[] = ['id' => 'ic-section-coverage', 'label' => '场景覆盖与作业流程'];
        }

        if (! empty($detail['scenes'])) {
            $sections[] = ['id' => 'ic-section-scenes', 'label' => '核心应用优势'];
        }

        $sections[] = ['id' => 'ic-section-related', 'label' => '更多解决方案'];

        return $sections;
    }

    /**
     * @return list<string>
     */
    public static function bulletLines(?string $text): array
    {
        if (blank($text)) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', (string) $text) ?: [];

        return array_values(array_filter(array_map(function (string $line): string {
            $line = trim($line);
            $line = preg_replace('/^[•\-\*]\s*/u', '', $line) ?? $line;

            return trim($line);
        }, $lines)));
    }
}

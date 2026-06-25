<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class GuanghengProductImporter
{
    private const BASE_URL = 'https://guangheng-zs.fkwcust.com';

    /** @var array<int, int> */
    private array $categoryMap = [];

    /** @var array<string, string> */
    private array $assetCache = [];

    public function __construct(
        private readonly string $locale = 'zh-cn',
    ) {}

    /**
     * @return array{categories: int, products: int, assets: int}
     */
    public function import(bool $deactivateExisting = true): array
    {
        app(CosStorageService::class)->applyDiskConfig();

        $catalog = $this->fetchCatalog();
        $nav = $this->fetchNav();

        if ($deactivateExisting) {
            Product::query()->forLocale($this->locale)->update(['is_active' => false]);
            Category::query()->forLocale($this->locale)->update(['is_active' => false]);
        }

        $categories = $this->collectCategories($catalog, $nav);
        $this->importCategories($categories);

        $imported = 0;
        foreach ($catalog['products'] as $index => $item) {
            $this->importProduct($item, $index);
            $imported++;
        }

        return [
            'categories' => count($this->categoryMap),
            'products' => $imported,
            'assets' => count($this->assetCache),
        ];
    }

    /** @return array<string, mixed> */
    private function fetchCatalog(): array
    {
        $html = $this->fetchHtml('/home/product/index?lang=zh-cn');

        foreach ($this->extractJsonBlocks($html) as $data) {
            if (is_array($data) && isset($data['products']) && is_array($data['products'])) {
                return $data;
            }
        }

        throw new RuntimeException('无法在源站产品列表页找到产品数据。');
    }

    /** @return array<string, mixed> */
    private function fetchNav(): array
    {
        $html = $this->fetchHtml('/home/index/index?lang=zh-cn');

        foreach ($this->extractJsonBlocks($html) as $data) {
            if (is_array($data) && isset($data['categories'], $data['children']) && ! isset($data['products'])) {
                return $data;
            }
        }

        throw new RuntimeException('无法在源站首页找到分类导航数据。');
    }

    /**
     * @param  array<string, mixed>  $catalog
     * @param  array<string, mixed>  $nav
     * @return list<array<string, mixed>>
     */
    private function collectCategories(array $catalog, array $nav): array
    {
        $byId = [];

        foreach ([
            $nav['categories'] ?? [],
            $catalog['megaCategories'] ?? [],
            $catalog['categories'] ?? [],
        ] as $chunk) {
            foreach ($chunk as $row) {
                if (is_array($row) && isset($row['id'])) {
                    $byId[(int) $row['id']] = $row;
                }
            }
        }

        foreach (($nav['children'] ?? []) as $children) {
            foreach ($children as $row) {
                if (is_array($row) && isset($row['id'])) {
                    $byId[(int) $row['id']] = $row;
                }
            }
        }

        foreach (($catalog['megaChildren'] ?? []) as $children) {
            foreach ($children as $row) {
                if (is_array($row) && isset($row['id'])) {
                    $byId[(int) $row['id']] = $row;
                }
            }
        }

        $categories = array_values($byId);
        usort($categories, function (array $a, array $b): int {
            $levelA = (int) ($a['level'] ?? ($a['parent_id'] ? 2 : 1));
            $levelB = (int) ($b['level'] ?? ($b['parent_id'] ? 2 : 1));

            return $levelA <=> $levelB ?: ((int) $a['id'] <=> (int) $b['id']);
        });

        return $categories;
    }

    /** @param list<array<string, mixed>> $categories */
    private function importCategories(array $categories): void
    {
        foreach ($categories as $index => $row) {
            $sourceId = (int) $row['id'];
            $parentSourceId = (int) ($row['parent_id'] ?? 0);
            $parentId = $parentSourceId > 0 ? ($this->categoryMap[$parentSourceId] ?? null) : null;

            $slug = 'gh-cat-'.$sourceId;
            $isRoot = $parentSourceId <= 0;

            $category = Category::query()->updateOrCreate(
                ['slug' => $slug, 'locale' => $this->locale],
                [
                    'parent_id' => $parentId,
                    'name' => (string) ($row['label'] ?? ('分类 '.$sourceId)),
                    'subtitle' => (string) ($row['subtitle'] ?? ''),
                    'description' => (string) ($row['description'] ?? $row['subtitle'] ?? ''),
                    'icon' => $this->migrateAsset($row['icon'] ?? null),
                    'cover_image' => $this->migrateAsset($row['cover_image'] ?? null),
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'is_home_show' => $isRoot,
                    'is_home_featured' => false,
                    'is_station_tab' => ! $isRoot,
                    'show_in_catalog' => ! $isRoot,
                    'link' => $isRoot ? '#home-products' : null,
                ],
            );

            $this->categoryMap[$sourceId] = $category->id;
        }
    }

    /** @param array<string, mixed> $item */
    private function importProduct(array $item, int $index): void
    {
        $sourceId = (int) $item['id'];
        $categoryId = $this->categoryMap[(int) ($item['category_id'] ?? 0)] ?? null;

        if (! $categoryId) {
            throw new RuntimeException("产品 {$sourceId} 的分类 {$item['category_id']} 未导入。");
        }

        $detail = $this->fetchProductDetail($sourceId);
        $name = $this->resolveProductName($item, $detail['title']);
        $subtitle = $this->resolveSubtitle($item, $detail);
        $summary = $this->resolveSummary($item, $detail);

        $showcaseImages = collect($detail['showcaseSlides'])
            ->pluck('image')
            ->filter()
            ->map(fn ($path) => $this->migrateAsset($path))
            ->filter()
            ->values()
            ->all();

        $detailGallery = collect($detail['gallery'])
            ->map(fn ($path) => $this->migrateAsset($path))
            ->filter()
            ->values()
            ->all();

        $detailFeatures = collect($detail['features'])
            ->map(fn (string $text) => ['text' => $text])
            ->values()
            ->all();

        $coverImage = $this->migrateAsset($item['image'] ?? null);
        $heroPoster = $this->migrateAsset($detail['showcaseSlides'][0]['image'] ?? $item['image'] ?? null);
        $detailHero = $this->migrateAsset($detail['detailHero'] ?? null);

        Product::query()->updateOrCreate(
            ['slug' => 'gh-prod-'.$sourceId, 'locale' => $this->locale],
            [
                'category_id' => $categoryId,
                'name' => $name,
                'model_no' => $this->resolveModelNo($item, $name),
                'subtitle' => $subtitle,
                'summary' => $summary,
                'metrics' => $this->normalizeMetrics($item['metrics'] ?? []),
                'cover_image' => $coverImage,
                'home_image' => $coverImage,
                'hero_poster' => $heroPoster,
                'showcase_images' => $showcaseImages ?: array_values(array_filter([$coverImage])),
                'detail_hero_image' => $detailHero,
                'detail_gallery' => $detailGallery,
                'detail_features' => $detailFeatures,
                'spec_groups' => $detail['specGroups'],
                'spec_document' => $this->migrateAsset($detail['downloadDoc']['url'] ?? null),
                'sort_order' => $index + 1,
                'is_active' => true,
                'is_home_show' => $index < 6,
                'is_home_featured' => $index === 0,
                'detail_url' => null,
            ],
        );
    }

    /** @return array{title: string, showcaseSlides: list<array<string, mixed>>, specGroups: list<array<string, mixed>>, downloadDoc: ?array<string, string>, gallery: list<string>, features: list<string>, detailHero: ?string} */
    private function fetchProductDetail(int $sourceId): array
    {
        $html = $this->fetchHtml('/home/product/detail.html?id='.$sourceId.'&lang=zh-cn');
        $title = '';
        if (preg_match('/<title>(.*?)<\/title>/s', $html, $matches)) {
            $title = trim(html_entity_decode(strip_tags($matches[1])));
        }

        $payload = [
            'title' => $title,
            'showcaseSlides' => [],
            'specGroups' => [],
            'downloadDoc' => null,
            'gallery' => [],
            'features' => [],
            'detailHero' => null,
        ];

        foreach ($this->extractJsonBlocks($html) as $data) {
            if (! is_array($data) || ! isset($data['specGroups'])) {
                continue;
            }

            $payload['showcaseSlides'] = is_array($data['showcaseSlides'] ?? null) ? $data['showcaseSlides'] : [];
            $payload['specGroups'] = is_array($data['specGroups'] ?? null) ? $data['specGroups'] : [];
            $payload['downloadDoc'] = is_array($data['downloadDoc'] ?? null) ? $data['downloadDoc'] : null;
            break;
        }

        if (preg_match('/details-hero-card[^>]*>.*?<img[^>]+src="([^"]+)"/s', $html, $heroMatch)) {
            $payload['detailHero'] = $heroMatch[1];
        }

        if (preg_match_all('/details-gallery-card[^>]*>.*?<img[^>]+src="([^"]+)"/s', $html, $galleryMatches)) {
            $payload['gallery'] = $galleryMatches[1];
        }

        if (preg_match_all('/detail-feature-card[^>]*>.*?<p[^>]*>(.*?)<\/p>/s', $html, $featureMatches)) {
            $payload['features'] = collect($featureMatches[1])
                ->map(fn (string $htmlChunk) => trim(html_entity_decode(strip_tags($htmlChunk))))
                ->filter()
                ->values()
                ->all();
        }

        return $payload;
    }

    /** @param array<string, mixed> $item */
    private function resolveProductName(array $item, string $detailTitle): string
    {
        $title = trim((string) preg_replace('/\s*-\s*产品详情.*$/u', '', $detailTitle));
        $listName = trim((string) ($item['name'] ?? ''));
        $categoryLabel = trim((string) ($item['category_label'] ?? ''));
        $genericNames = ['ZSKJ-GCJ-03', '灌缝设备'];

        if ($listName !== '' && ! in_array($listName, $genericNames, true)) {
            return $listName;
        }

        if ($title !== '' && ! in_array($title, $genericNames, true)) {
            return $title;
        }

        if ($categoryLabel !== '' && ! in_array($categoryLabel, ['洗地机系列', '垃圾收纳系列', '智能清扫系列', '灌缝设备'], true)) {
            return $categoryLabel;
        }

        $slideTitle = trim((string) ($item['subtitle'] ?? ''));
        if ($slideTitle !== '' && ! in_array($slideTitle, $genericNames, true) && mb_strlen($slideTitle) <= 40) {
            return $slideTitle;
        }

        return $listName !== '' ? $listName : ($categoryLabel !== '' ? $categoryLabel : '产品 '.$item['id']);
    }

    /**
     * @param array<string, mixed> $item
     * @param array{showcaseSlides: list<array<string, mixed>>} $detail
     */
    private function resolveSubtitle(array $item, array $detail): ?string
    {
        $slideDescription = trim((string) ($detail['showcaseSlides'][0]['description'] ?? ''));
        if ($slideDescription !== '') {
            return Str::limit($slideDescription, 255, '...');
        }

        $subtitle = trim((string) ($item['subtitle'] ?? ''));
        if ($subtitle !== '' && $subtitle !== ($item['name'] ?? '')) {
            return Str::limit($subtitle, 255, '...');
        }

        $summary = trim((string) ($item['summary'] ?? ''));

        return $summary !== '' ? Str::limit($summary, 255, '...') : null;
    }

    /**
     * @param array<string, mixed> $item
     * @param array{showcaseSlides: list<array<string, mixed>>} $detail
     */
    private function resolveSummary(array $item, array $detail): ?string
    {
        $slideDescription = trim((string) ($detail['showcaseSlides'][0]['description'] ?? ''));
        if ($slideDescription !== '') {
            return $slideDescription;
        }

        $summary = trim((string) ($item['summary'] ?? ''));

        return $summary !== '' ? $summary : null;
    }

    /** @param array<string, mixed> $item */
    private function resolveModelNo(array $item, string $name): ?string
    {
        $model = trim((string) ($item['model'] ?? ''));
        if ($model !== '' && $model !== $name && mb_strlen($model) <= 80) {
            return $model;
        }

        $label = trim((string) ($item['category_label'] ?? ''));
        if (preg_match('/^(ZS[A-Z0-9\-\/]+|ZSKJ-[A-Z0-9\-]+)/', $label)) {
            return $label;
        }

        if (preg_match('/(ZS[A-Z0-9\-\/]+|ZSKJ-[A-Z0-9\-]+)/', $name, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /** @param mixed $metrics */
    private function normalizeMetrics(mixed $metrics): array
    {
        if (! is_array($metrics)) {
            return [];
        }

        $rows = [];
        foreach ($metrics as $row) {
            if (is_array($row) && isset($row['value'], $row['label'])) {
                $rows[] = ['value' => (string) $row['value'], 'label' => (string) $row['label']];
            } elseif (is_array($row) && count($row) >= 2) {
                $rows[] = ['value' => (string) $row[0], 'label' => (string) $row[1]];
            }
        }

        return $rows;
    }

    private function migrateAsset(?string $path): ?string
    {
        $path = is_string($path) ? trim($path) : null;
        if ($path === null || $path === '') {
            return null;
        }

        if (isset($this->assetCache[$path])) {
            return $this->assetCache[$path];
        }

        $url = str_starts_with($path, 'http') ? $path : rtrim(self::BASE_URL, '/').'/'.ltrim($path, '/');
        $response = Http::timeout(60)->retry(2, 500)->get($url);
        if (! $response->successful()) {
            return null;
        }

        $relative = 'imports/guangheng/'.ltrim(str_replace(['../', '..\\'], '', ltrim($path, '/')), '/');
        $disk = upload_disk();

        try {
            Storage::disk($disk)->put($relative, $response->body(), 'public');
        } catch (Throwable) {
            Storage::disk('public')->put($relative, $response->body());
        }

        return $this->assetCache[$path] = $relative;
    }

    private function fetchHtml(string $path): string
    {
        $url = str_starts_with($path, 'http') ? $path : rtrim(self::BASE_URL, '/').$path;
        $response = Http::timeout(60)->retry(2, 500)->get($url);
        if (! $response->successful()) {
            throw new RuntimeException("请求失败：{$url} ({$response->status()})");
        }

        return $response->body();
    }

    /** @return list<mixed> */
    private function extractJsonBlocks(string $html): array
    {
        preg_match_all('/<script[^>]*type="application\/json"[^>]*>(.*?)<\/script>/s', $html, $matches);
        $blocks = [];

        foreach ($matches[1] as $raw) {
            try {
                $blocks[] = json_decode(html_entity_decode($raw), true, 512, JSON_THROW_ON_ERROR);
            } catch (Throwable) {
                continue;
            }
        }

        return $blocks;
    }
}

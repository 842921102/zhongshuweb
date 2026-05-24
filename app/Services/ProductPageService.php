<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPageSetting;
use App\Support\MediaUrl;
use Illuminate\Support\Collection;

class ProductPageService
{
    public function __construct(
        public string $locale = 'zh-cn',
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function indexData(?string $categoryParam = null): array
    {
        $settings = ProductPageSetting::forLocale($this->locale);
        $labels = $settings->listLabels();

        $roots = Category::query()
            ->forLocale($this->locale)
            ->active()
            ->roots()
            ->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $products = Product::query()
            ->forLocale($this->locale)
            ->active()
            ->with('category.parent')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        [$activeSeriesKey, $activeCatalogTab] = $this->resolveCategorySelection($categoryParam, $roots);

        $seriesProducts = $this->productsForSeries($products, $activeSeriesKey, $roots);
        $catalogProducts = $this->productsForCatalogTab($seriesProducts, $activeCatalogTab, $activeSeriesKey);

        $activeRoot = $roots->first(fn (Category $r) => $r->domKey() === $activeSeriesKey) ?? $roots->first();

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'pageSettings' => $settings,
            'labels' => $labels,
            'activeSeriesKey' => $activeSeriesKey,
            'activeCatalogTab' => $activeCatalogTab,
            'activeRoot' => $activeRoot,
            'catalogProducts' => $catalogProducts,
            'productPageJson' => $this->buildClientJson($roots, $products, $labels, $activeSeriesKey, $activeCatalogTab),
        ]);
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function resolveCategorySelection(?string $param, Collection $roots): array
    {
        $firstRoot = $roots->first();
        $defaultSeries = $firstRoot?->domKey() ?? 'category-0';
        $defaultTab = 'all:'.$defaultSeries;

        if (blank($param)) {
            return [$defaultSeries, $defaultTab];
        }

        if (str_starts_with($param, 'all:')) {
            $seriesKey = substr($param, 4);

            return [$seriesKey, $param];
        }

        if (! str_starts_with($param, 'category-')) {
            $cat = Category::query()->forLocale($this->locale)->where('slug', $param)->first();
            if ($cat) {
                $param = $cat->domKey();
            }
        }

        $category = Category::query()
            ->forLocale($this->locale)
            ->with('parent')
            ->get()
            ->first(fn (Category $c) => $c->domKey() === $param);

        if (! $category) {
            return [$defaultSeries, $defaultTab];
        }

        if ($category->isRoot()) {
            return [$category->domKey(), 'all:'.$category->domKey()];
        }

        $parent = $category->parent;

        return [$parent?->domKey() ?? $defaultSeries, $category->domKey()];
    }

    /**
     * @param  Collection<int, Product>  $products
     * @param  Collection<int, Category>  $roots
     * @return Collection<int, Product>
     */
    private function productsForSeries(Collection $products, string $seriesKey, Collection $roots): Collection
    {
        $root = $roots->first(fn (Category $r) => $r->domKey() === $seriesKey);
        if (! $root) {
            return collect();
        }

        $childIds = $root->children->pluck('id')->all();

        return $products->filter(function (Product $p) use ($root, $childIds): bool {
            $cat = $p->category;
            if (! $cat) {
                return false;
            }

            return $cat->id === $root->id
                || $cat->parent_id === $root->id
                || in_array($cat->id, $childIds, true);
        })->values();
    }

    /**
     * @param  Collection<int, Product>  $seriesProducts
     * @return Collection<int, Product>
     */
    private function productsForCatalogTab(Collection $seriesProducts, string $tab, string $seriesKey): Collection
    {
        if (str_starts_with($tab, 'all:')) {
            return $seriesProducts;
        }

        return $seriesProducts->filter(
            fn (Product $p) => $p->category && $p->category->domKey() === $tab
        )->values();
    }

    /**
     * @param  Collection<int, Category>  $roots
     * @param  Collection<int, Product>  $products
     * @param  array<string, string>  $labels
     * @return array<string, mixed>
     */
    private function buildClientJson(
        Collection $roots,
        Collection $products,
        array $labels,
        string $activeSeriesKey,
        string $activeCatalogTab,
    ): array {
        $mapCategory = function (Category $c, int $level): array {
            return [
                'id' => $c->id,
                'parent_id' => $c->parent_id ?? 0,
                'level' => $level,
                'key' => $c->domKey(),
                'label' => $c->name,
                'subtitle' => $c->subtitle,
                'description' => $c->description ?? $c->subtitle,
                'icon' => MediaUrl::resolve($c->icon, ''),
                'cover_image' => MediaUrl::resolve($c->cover_image, ''),
            ];
        };

        $megaChildren = [];
        foreach ($roots as $root) {
            $megaChildren[$root->domKey()] = $root->children->map(fn (Category $c) => $mapCategory($c, 2))->values()->all();
        }

        $productRows = $products->map(function (Product $p): array {
            $cat = $p->category;

            return [
                'id' => $p->id,
                'category_id' => $cat?->id,
                'category_key' => $cat?->domKey(),
                'category_label' => $cat?->name,
                'parent_key' => $cat?->parent?->domKey(),
                'model' => $p->seriesLabel(),
                'name' => $p->name,
                'subtitle' => $p->subtitle,
                'image' => media_url($p->displayImage()),
                'metrics' => $p->metricPairs(),
                'detailHref' => $p->url(),
            ];
        })->values()->all();

        return [
            'megaChildren' => $megaChildren,
            'products' => $productRows,
            'initialCategory' => $activeSeriesKey,
            'initialCatalogCategory' => $activeCatalogTab,
            'labels' => $labels,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function showData(string $slugOrId): ?array
    {
        $product = Product::query()
            ->forLocale($this->locale)
            ->active()
            ->with('category.parent')
            ->where(function ($q) use ($slugOrId): void {
                $q->where('slug', $slugOrId);
                if (is_numeric($slugOrId)) {
                    $q->orWhere('id', (int) $slugOrId);
                }
            })
            ->first();

        if (! $product) {
            return null;
        }

        $specGroups = $product->specGroupList();
        $showcaseSlides = collect($product->showcaseImageList())
            ->values()
            ->map(function (string $path, int $index) use ($product) {
                $n = $index + 1;

                return [
                    'image' => MediaUrl::resolve($path),
                    'alt' => $product->name.' '.$n,
                    'label' => sprintf('%02d / 产品展示', $n),
                ];
            })
            ->all();

        $rights = is_array($product->rights_content) ? $product->rights_content : [];
        $pageSettings = ProductPageSetting::forLocale($this->locale);
        $detailLabels = $pageSettings->mergedDetailLabels();

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'product' => $product,
            'pageSettings' => $pageSettings,
            'specGroups' => $specGroups,
            'defaultSpecKey' => $specGroups[0]['key'] ?? '',
            'showcaseSlides' => $showcaseSlides,
            'rights' => $rights,
            'detailPageJson' => [
                'showcaseSlides' => $showcaseSlides,
                'specGroups' => $specGroups,
                'defaultSpecKey' => $specGroups[0]['key'] ?? '',
                'downloadDoc' => $product->specDocumentUrl() ? [
                    'title' => $product->name,
                    'url' => $product->specDocumentUrl(),
                ] : null,
                'submitUrl' => localized_route('products.consult', ['product' => $product->slug ?: $product->id], $this->locale),
                'labels' => $detailLabels,
            ],
            'detailLabels' => $detailLabels,
        ]);
    }

    /** @return array<string, string> */
    public static function defaultDetailLabels(): array
    {
        return [
            'contact_now' => '立即咨询',
            'view_specs' => '查看参数',
            'breadcrumb_home' => '首页',
            'breadcrumb_list' => '产品中心',
            'details_overline' => 'DETAILS · 图文详情',
            'specs_overline' => 'SPECIFICATIONS',
            'specs_title' => '核心技术参数',
            'specs_item' => '参数项目',
            'specs_value' => '规格数值',
            'specs_notice' => '* 规格参数以最终交付产品为准。',
            'download_specs' => '→ 下载完整资料',
            'contact_overline' => 'CONTACT US',
            'contact_desc' => '留下您的信息，我们会尽快与您联系。',
            'form_title' => '咨询联系',
            'form_submit' => '提交咨询',
            'form_success' => '咨询信息已提交，我们会尽快与您联系。',
            'form_error' => '提交失败，请稍后重试。',
            'download_missing' => '暂无可下载资料。',
            'specs_empty' => '暂无参数信息',
        ];
    }

}

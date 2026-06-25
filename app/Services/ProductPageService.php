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
        $layout = new SiteLayoutService($this->locale);
        $shared = $layout->shared();

        $settings = ProductPageSetting::forLocale($this->locale);
        $labels = $settings->listLabels();
        $roots = $shared['navCategories'];

        $activeRoot = 'all';
        $activeSub = null;
        [$activeRoot, $activeSub] = $this->resolveCategorySelection($categoryParam, $roots);

        $catalogProducts = $this->loadCatalogProducts($activeRoot, $activeSub, $roots);

        $catalogTabsEnabled = (bool) $settings->catalog_tabs_enabled;
        $catalogTabs = $catalogTabsEnabled ? $roots : collect();
        $catalogSubTabs = $this->catalogSubTabsForRoot($activeRoot, $roots);

        return array_merge($shared, [
            'pageSettings' => $settings,
            'labels' => $labels,
            'activeRoot' => $activeRoot,
            'activeSub' => $activeSub,
            'catalogTabsEnabled' => $catalogTabsEnabled,
            'catalogTabs' => $catalogTabs,
            'catalogSubTabs' => $catalogSubTabs,
            'catalogProducts' => $catalogProducts,
            'productPageJson' => $this->buildClientJson(
                $roots,
                $catalogProducts,
                $labels,
                $activeRoot,
                $activeSub,
                $catalogTabsEnabled,
            ),
        ]);
    }

    /**
     * @return array{products: list<array<string, mixed>>}
     */
    public function catalogJson(?string $categoryParam = null): array
    {
        $roots = (new SiteLayoutService($this->locale))->shared()['navCategories'];
        [$activeRoot, $activeSub] = $this->resolveCategorySelection($categoryParam, $roots);
        $products = $this->loadCatalogProducts($activeRoot, $activeSub, $roots);

        return [
            'products' => $this->mapProductRows($products),
        ];
    }

    /**
     * @param  Collection<int, Category>  $roots
     * @return Collection<int, Product>
     */
    private function loadCatalogProducts(string $activeRoot, ?string $activeSub, Collection $roots): Collection
    {
        $query = $this->catalogProductQuery();

        if ($activeRoot !== 'all') {
            $root = $roots->first(fn (Category $r) => $r->domKey() === $activeRoot);
            if (! $root) {
                return collect();
            }

            $categoryIds = $root->children->pluck('id')->push($root->id)->all();

            if (filled($activeSub) && $activeSub !== 'all') {
                $sub = $root->children->first(fn (Category $c) => $c->domKey() === $activeSub);
                if (! $sub) {
                    return collect();
                }
                $query->where('category_id', $sub->id);
            } else {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        return $query->get();
    }

  /** @return \Illuminate\Database\Eloquent\Builder<Product> */
    private function catalogProductQuery()
    {
        return Product::query()
            ->forLocale($this->locale)
            ->active()
            ->with(['category.parent'])
            ->select([
                'id', 'category_id', 'name', 'subtitle', 'model_no', 'slug',
                'cover_image', 'home_image', 'metrics', 'detail_url',
                'sort_order', 'locale', 'is_active',
            ])
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return list<array<string, mixed>>
     */
    private function mapProductRows(Collection $products): array
    {
        return $products->map(function (Product $p): array {
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
    }

    /**
     * @return array{0: string, 1: string|null}
     */
    private function resolveCategorySelection(?string $param, Collection $roots): array
    {
        if (blank($param) || $param === 'all') {
            return ['all', null];
        }

        if (str_starts_with($param, 'all:')) {
            $rootKey = substr($param, 4);

            if (blank($rootKey) || $rootKey === 'all') {
                return ['all', null];
            }

            if ($roots->contains(fn (Category $r) => $r->domKey() === $rootKey)) {
                return [$rootKey, 'all'];
            }

            return ['all', null];
        }

        if (! str_starts_with($param, 'category-')) {
            $cat = Category::query()
                ->forLocale($this->locale)
                ->active()
                ->where('slug', $param)
                ->first();
            if ($cat) {
                $param = $cat->domKey();
            }
        }

        $category = Category::query()
            ->forLocale($this->locale)
            ->active()
            ->with('parent')
            ->where(function ($query) use ($param): void {
                if (str_starts_with($param, 'category-') && is_numeric(substr($param, 9))) {
                    $query->where('id', (int) substr($param, 9));
                } else {
                    $query->where('slug', $param);
                }
            })
            ->first();

        if (! $category) {
            return ['all', null];
        }

        if ($category->isRoot()) {
            return [$category->domKey(), 'all'];
        }

        $parentKey = $category->parent?->domKey();

        if (! $parentKey || ! $roots->contains(fn (Category $r) => $r->domKey() === $parentKey)) {
            return ['all', null];
        }

        return [$parentKey, $category->domKey()];
    }

    /**
     * @param  Collection<int, Category>  $roots
     * @return Collection<int, Category>
     */
    private function catalogSubTabsForRoot(string $activeRoot, Collection $roots): Collection
    {
        if ($activeRoot === 'all') {
            return collect();
        }

        $root = $roots->first(fn (Category $r) => $r->domKey() === $activeRoot);

        return $root?->children
            ->filter(fn (Category $child) => $child->show_in_catalog)
            ->values() ?? collect();
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
        string $activeRoot,
        ?string $activeSub,
        bool $catalogTabsEnabled = true,
    ): array {
        $catalogChildren = [];
        foreach ($roots as $root) {
            $catalogChildren[$root->domKey()] = $root->children
                ->filter(fn (Category $child) => $child->show_in_catalog)
                ->map(fn (Category $child): array => [
                    'key' => $child->domKey(),
                    'label' => $child->name,
                ])
                ->values()
                ->all();
        }

        return [
            'catalogRoots' => $roots->map(fn (Category $root): array => [
                'key' => $root->domKey(),
                'label' => $root->name,
            ])->values()->all(),
            'catalogChildren' => $catalogChildren,
            'products' => $this->mapProductRows($products),
            'initialRoot' => $activeRoot,
            'initialSub' => $activeSub,
            'catalogTabsEnabled' => $catalogTabsEnabled,
            'labels' => $labels,
            'catalogApiUrl' => localized_route('api.products.catalog', [], $this->locale, false),
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

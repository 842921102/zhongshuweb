<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Banner;
use App\Models\CaseStudy;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\CompanyPageSetting;
use App\Models\Product;
use App\Models\SitePartner;
use App\Models\SiteStatistic;
use Illuminate\Support\Collection;

class HomePageService
{
    public function __construct(
        public string $locale = 'zh-cn',
    ) {}

    public function data(): array
    {
        $sections = HomeSection::mapForLocale($this->locale);

        $rootCategories = Category::query()
            ->forLocale($this->locale)
            ->active()
            ->homeRoots()
            ->orderBy('sort_order')
            ->get();

        $featuredRoot = $rootCategories
            ->filter(fn (Category $c) => $c->is_home_featured)
            ->sortBy(fn (Category $c) => [filled($c->cover_image) ? 0 : 1, $c->sort_order])
            ->first()
            ?? $rootCategories->first();

        $gridRoots = $rootCategories->filter(
            fn (Category $c) => $featuredRoot === null || $c->id !== $featuredRoot->id
        )->values();

        $stationTabs = Category::query()
            ->forLocale($this->locale)
            ->active()
            ->stationTabs()
            ->orderBy('sort_order')
            ->get();

        $productsByCategory = Product::query()
            ->forLocale($this->locale)
            ->active()
            ->home()
            ->whereIn('category_id', $stationTabs->pluck('id'))
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category_id');

        $stationPanels = $stationTabs->map(function (Category $tab) use ($productsByCategory) {
            $products = $productsByCategory->get($tab->id, collect());
            $featured = $products->firstWhere('is_home_featured', true) ?? $products->first();
            $others = $products->filter(fn (Product $p) => $featured === null || $p->id !== $featured->id)->values();

            return [
                'tab' => $tab,
                'featured' => $featured,
                'others' => $others,
            ];
        });

        $orderedSections = HomeSection::sortedEnabled($sections);

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'sections' => $sections,
            'orderedSections' => $orderedSections,
            'heroScrollTarget' => HomeSection::heroScrollTarget($sections),
            'banners' => Banner::query()
                ->forLocale($this->locale)
                ->active()
                ->home()
                ->orderBy('sort_order')
                ->get(),
            'solutionsFeatured' => $featuredRoot,
            'solutionsGrid' => $gridRoots,
            'stationPanels' => $stationPanels,
            'cases' => CaseStudy::query()
                ->forLocale($this->locale)
                ->active()
                ->home()
                ->orderBy('sort_order')
                ->limit(5)
                ->get(),
            'partners' => SitePartner::query()
                ->forLocale($this->locale)
                ->active()
                ->home()
                ->orderBy('sort_order')
                ->limit(12)
                ->get(),
            'statistics' => SiteStatistic::query()
                ->forLocale($this->locale)
                ->active()
                ->home()
                ->orderBy('sort_order')
                ->limit(4)
                ->get(),
            'articles' => $this->homeArticles(),
            'about' => CompanyPageSetting::forLocale($this->locale),
        ]);
    }

    protected function homeArticles(): Collection
    {
        $featured = Article::query()
            ->forLocale($this->locale)
            ->published()
            ->home()
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        if ($featured->isNotEmpty()) {
            return $featured;
        }

        return Article::query()
            ->forLocale($this->locale)
            ->published()
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();
    }

    public function section(string $key, ?string $defaultTitle = null): ?HomeSection
    {
        $sections = HomeSection::mapForLocale($this->locale);

        return $sections[$key] ?? null;
    }

    public function sectionEnabled(string $key): bool
    {
        $section = $this->section($key);

        return $section === null || $section->isEnabled();
    }
}

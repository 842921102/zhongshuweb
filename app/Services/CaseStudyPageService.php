<?php

namespace App\Services;

use App\Models\CasePageSetting;
use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CaseStudyPageService
{
    public function __construct(
        public string $locale = 'zh-cn',
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function indexData(?string $categorySlug = null, int $perPage = 12): array
    {
        $settings = CasePageSetting::forLocale($this->locale);
        $categories = CaseStudyCategory::query()
            ->forLocale($this->locale)
            ->active()
            ->orderBy('sort_order')
            ->get();

        $featured = CaseStudy::query()
            ->forLocale($this->locale)
            ->active()
            ->published()
            ->featured()
            ->with('category')
            ->orderBy('sort_order')
            ->limit(9)
            ->get();

        if ($featured->isEmpty()) {
            $featured = CaseStudy::query()
                ->forLocale($this->locale)
                ->active()
                ->published()
                ->with('category')
                ->orderBy('sort_order')
                ->limit(3)
                ->get();
        }

        $activeCategory = $categorySlug && $categorySlug !== 'all'
            ? $categories->firstWhere('slug', $categorySlug)
            : null;

        $listQuery = CaseStudy::query()
            ->forLocale($this->locale)
            ->active()
            ->published()
            ->with('category')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order');

        if ($activeCategory) {
            $listQuery->where('category_id', $activeCategory->id);
        }

        if ($featured->isNotEmpty()) {
            $listQuery->whereNotIn('id', $featured->pluck('id'));
        }

        /** @var LengthAwarePaginator<int, CaseStudy> $cases */
        $cases = $listQuery->paginate($perPage)->withQueryString();

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'pageSettings' => $settings,
            'categories' => $categories,
            'activeCategorySlug' => $categorySlug ?: 'all',
            'featuredCases' => $featured,
            'cases' => $cases,
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function showData(string $slug): ?array
    {
        $case = CaseStudy::query()
            ->forLocale($this->locale)
            ->active()
            ->published()
            ->with('category')
            ->where('slug', $slug)
            ->first();

        if ($case === null) {
            return null;
        }

        $related = CaseStudy::query()
            ->forLocale($this->locale)
            ->active()
            ->published()
            ->with('category')
            ->where('id', '!=', $case->id)
            ->when($case->category_id, fn ($q) => $q->where('category_id', $case->category_id))
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'case' => $case,
            'relatedCases' => $related,
        ]);
    }

    /**
     * @return Collection<int, CaseStudy>
     */
    public function homeCases(int $limit = 5): Collection
    {
        return CaseStudy::query()
            ->forLocale($this->locale)
            ->active()
            ->published()
            ->home()
            ->with('category')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }
}

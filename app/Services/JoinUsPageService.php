<?php

namespace App\Services;

use App\Models\JoinCultureCard;
use App\Models\JoinJobCategory;
use App\Models\JoinPageSetting;
use App\Models\JoinPosition;
use App\Models\JoinProcessStep;
use App\Models\JoinWelfareCard;
use App\Models\JoinWhyCard;
use Illuminate\Support\Collection;

class JoinUsPageService
{
    public function __construct(
        public string $locale = 'zh-cn',
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function indexData(?string $categorySlug = null): array
    {
        $settings = JoinPageSetting::forLocale($this->locale);

        $categories = JoinJobCategory::query()
            ->forLocale($this->locale)
            ->active()
            ->orderBy('sort_order')
            ->get();

        $activeCategory = $categorySlug && $categorySlug !== 'all'
            ? $categories->firstWhere('slug', $categorySlug)
            : null;

        $positionsQuery = JoinPosition::query()
            ->forLocale($this->locale)
            ->active()
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('id');

        if ($activeCategory) {
            $positionsQuery->where('category_id', $activeCategory->id);
        }

        $positions = $positionsQuery->get();

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'pageSettings' => $settings,
            'whyCards' => JoinWhyCard::query()->forLocale($this->locale)->active()->orderBy('sort_order')->get(),
            'cultureCards' => JoinCultureCard::query()->forLocale($this->locale)->active()->orderBy('sort_order')->get(),
            'categories' => $categories,
            'activeCategorySlug' => $categorySlug ?: 'all',
            'positions' => $positions,
            'processSteps' => JoinProcessStep::query()->forLocale($this->locale)->active()->orderBy('sort_order')->get(),
            'welfareCards' => JoinWelfareCard::query()->forLocale($this->locale)->active()->orderBy('sort_order')->get(),
            'positionOptions' => JoinPosition::query()
                ->forLocale($this->locale)
                ->active()
                ->orderBy('sort_order')
                ->get(['id', 'title']),
            'joinPageJson' => $this->buildClientJson($categories, $positions),
        ]);
    }

    /**
     * @param  Collection<int, JoinJobCategory>  $categories
     * @param  Collection<int, JoinPosition>  $positions
     * @return array<string, mixed>
     */
    private function buildClientJson(Collection $categories, Collection $positions): array
    {
        return [
            'categories' => $categories->map(fn (JoinJobCategory $c) => [
                'slug' => $c->slug,
                'name' => $c->name,
            ])->values()->all(),
            'positions' => $positions->map(fn (JoinPosition $p) => [
                'id' => $p->id,
                'category_slug' => $p->category?->slug,
            ])->values()->all(),
        ];
    }
}

<?php

namespace App\Services;

use App\Models\IndustrySolution;
use App\Models\IndustrySolutionPageSetting;
use Illuminate\Support\Collection;

class IndustrySolutionPageService
{
    public function __construct(
        public string $locale = 'zh-cn',
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function indexData(): array
    {
        $settings = IndustrySolutionPageSetting::forLocale($this->locale);

        /** @var Collection<int, IndustrySolution> $solutions */
        $solutions = IndustrySolution::query()
            ->forLocale($this->locale)
            ->active()
            ->published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'pageSettings' => $settings,
            'solutions' => $solutions,
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function showData(string $slug): ?array
    {
        $solution = IndustrySolution::query()
            ->forLocale($this->locale)
            ->active()
            ->published()
            ->where('slug', $slug)
            ->first();

        if ($solution === null) {
            return null;
        }

        $others = IndustrySolution::query()
            ->forLocale($this->locale)
            ->active()
            ->published()
            ->where('id', '!=', $solution->id)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'pageSettings' => IndustrySolutionPageSetting::forLocale($this->locale),
            'solution' => $solution,
            'otherSolutions' => $others,
        ]);
    }
}

<?php

namespace App\Services;

use App\Models\CompanyCultureValue;
use App\Models\CompanyHonor;
use App\Models\CompanyMilestone;
use App\Models\CompanyTeamMember;
use App\Models\CompanyPageSetting;
use Illuminate\Support\Collection;

class CompanyPageService
{
    public function __construct(
        public string $locale = 'zh-cn',
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        $settings = CompanyPageSetting::forLocale($this->locale);

        $pathSteps = CompanyMilestone::query()
            ->forLocale($this->locale)
            ->active()
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'settings' => $settings,
            'pathSteps' => $pathSteps,
            'capabilities' => $settings->normalizedCapabilities(),
            'cultureValues' => CompanyCultureValue::query()
                ->forLocale($this->locale)
                ->active()
                ->orderBy('sort_order')
                ->get(),
            'honors' => CompanyHonor::query()
                ->forLocale($this->locale)
                ->active()
                ->orderBy('sort_order')
                ->get(),
            'teamFeatured' => CompanyTeamMember::query()
                ->forLocale($this->locale)
                ->active()
                ->featured()
                ->orderBy('sort_order')
                ->first(),
            'teamMembers' => CompanyTeamMember::query()
                ->forLocale($this->locale)
                ->active()
                ->where('is_featured', false)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}

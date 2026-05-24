<?php

namespace App\Services;

use App\Models\Category;
use App\Models\ProductPageSetting;
use App\Models\SiteFooterLink;
use App\Models\SiteNavMenu;
use App\Models\SiteSetting;
use App\Support\MediaUrl;
use Illuminate\Support\Collection;

class SiteLayoutService
{
    public function __construct(
        public string $locale = 'zh-cn',
    ) {}

    /**
     * Header / footer variables required by layouts.home.
     *
     * @return array<string, mixed>
     */
    public function shared(): array
    {
        $navCategories = Category::query()
            ->forLocale($this->locale)
            ->active()
            ->roots()
            ->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return [
            'locale' => $this->locale,
            'navMenus' => SiteNavMenu::headerMenus($this->locale),
            'productNavJson' => $this->productNavJson($navCategories),
            'footer' => $this->footerData(),
            'siteName' => SiteSetting::get('site_name', '众鼠科技'),
            'siteDescription' => SiteSetting::get('site_description', ''),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function footerData(): array
    {
        $links = SiteFooterLink::query()
            ->forLocale($this->locale)
            ->where('is_active', true)
            ->orderBy('group_key')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('group_key');

        return [
            'company_name' => SiteSetting::get('company_name', SiteSetting::get('site_name', '众鼠科技')),
            'logo' => SiteSetting::get('footer_logo'),
            'tagline' => SiteSetting::get('footer_tagline'),
            'phone' => SiteSetting::get('contact_phone'),
            'email' => SiteSetting::get('contact_email'),
            'address' => SiteSetting::get('contact_address'),
            'copyright' => SiteSetting::get('footer_copyright'),
            'icp' => SiteSetting::get('icp_number'),
            'social' => json_decode(SiteSetting::get('social_links', '[]'), true) ?: [],
            'link_groups' => $links,
        ];
    }

    /**
     * @return array{categories: list<array<string, mixed>>, children: array<string, list<array<string, mixed>>>, labels: array<string, string>}
     */
    public function productNavJson(Collection $roots): array
    {
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
                'product_count' => 0,
            ];
        };

        $categories = $roots->map(fn (Category $c) => $mapCategory($c, 1))->values()->all();

        $children = [];
        foreach ($roots as $root) {
            $children[$root->domKey()] = $root->children
                ->map(fn (Category $c) => $mapCategory($c, 2))
                ->values()
                ->all();
        }

        $pageLabels = ProductPageSetting::forLocale($this->locale)->navLabels();

        return [
            'categories' => $categories,
            'children' => $children,
            'labels' => $pageLabels,
        ];
    }
}

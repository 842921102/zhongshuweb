<?php

use App\Models\SiteNavMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['zh-cn', 'en-us'] as $locale) {
            SiteNavMenu::ensureDefaults($locale);

            $shifts = [
                'joinus' => 9,
                'support' => 8,
                'news' => 7,
                'culture' => 6,
                'about' => 5,
                'case_center' => 4,
            ];

            foreach ($shifts as $menuKey => $order) {
                SiteNavMenu::query()
                    ->where('menu_key', $menuKey)
                    ->where('locale', $locale)
                    ->update(['sort_order' => $order]);
            }

            SiteNavMenu::query()->updateOrCreate(
                ['menu_key' => 'industry_cases', 'locale' => $locale],
                [
                    'parent_id' => null,
                    'menu_type' => SiteNavMenu::TYPE_LINK,
                    'label' => $locale === 'zh-cn' ? '解决方案' : 'Solutions',
                    'url' => '/industry-cases',
                    'route_keys' => 'industry,industry-cases',
                    'search_keywords' => '解决方案 行业方案 industry cases solutions',
                    'open_in_new_tab' => false,
                    'sort_order' => 3,
                    'is_active' => true,
                ]
            );
        }
    }

    public function down(): void
    {
        SiteNavMenu::query()->where('menu_key', 'industry_cases')->delete();

        $restore = [
            'case_center' => 3,
            'about' => 4,
            'culture' => 5,
            'news' => 6,
            'support' => 7,
            'joinus' => 8,
        ];

        foreach (['zh-cn', 'en-us'] as $locale) {
            foreach ($restore as $menuKey => $order) {
                SiteNavMenu::query()
                    ->where('menu_key', $menuKey)
                    ->where('locale', $locale)
                    ->update(['sort_order' => $order]);
            }
        }
    }
};

<?php

use App\Models\SiteNavMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['zh-cn', 'en-us'] as $locale) {
            SiteNavMenu::ensureDefaults($locale);
        }
    }

    public function down(): void
    {
        SiteNavMenu::query()
            ->where('menu_key', 'case_center')
            ->delete();

        SiteNavMenu::query()
            ->where('menu_key', 'case')
            ->update([
                'url' => '/cases',
                'route_keys' => 'case,cases',
                'search_keywords' => '案例 case,cases',
            ]);
    }
};

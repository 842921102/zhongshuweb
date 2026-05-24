<?php

use App\Models\SiteNavMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // 无独立页面：投资人关系、加入我们（仅指向页脚锚点）
        // 重复入口：首页案例区锚点（已有 /cases 招商加盟页）
        SiteNavMenu::query()
            ->whereIn('menu_key', ['case', 'relation', 'joinus'])
            ->delete();

        SiteNavMenu::query()
            ->where('menu_key', 'case_center')
            ->update([
                'label' => '招商加盟',
                'url' => '/cases',
                'route_keys' => 'case,cases',
                'search_keywords' => '招商加盟 案例 cases',
            ]);
    }

    public function down(): void
    {
        SiteNavMenu::ensureDefaults('zh-cn');
        SiteNavMenu::ensureDefaults('en-us');
    }
};

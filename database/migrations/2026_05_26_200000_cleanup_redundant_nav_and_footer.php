<?php

use App\Models\SiteFooterLink;
use App\Models\SiteNavMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // 企业文化已并入「关于我们」页，不再单独占顶部导航位
        SiteNavMenu::query()->where('menu_key', 'culture')->delete();

        foreach (['zh-cn', 'en-us'] as $locale) {
            SiteNavMenu::ensureDefaults($locale);
        }

        // 页脚：去掉重复 /cases 与失效锚点
        SiteFooterLink::query()
            ->whereIn('label', ['项目案例', '招商加盟', '应用案例'])
            ->delete();

        $footerLinks = [
            ['group_key' => 'solutions', 'group_label' => '解决方案', 'label' => '行业方案', 'url' => '/industry-cases', 'sort_order' => 0],
            ['group_key' => 'solutions', 'group_label' => '解决方案', 'label' => '客户案例', 'url' => '/cases', 'sort_order' => 1],
        ];

        foreach (['zh-cn', 'en-us'] as $locale) {
            foreach ($footerLinks as $row) {
                SiteFooterLink::query()->updateOrCreate(
                    ['group_key' => $row['group_key'], 'label' => $row['label'], 'locale' => $locale],
                    array_merge($row, ['is_active' => true, 'locale' => $locale])
                );
            }
        }
    }

    public function down(): void
    {
        //
    }
};

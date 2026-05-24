<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Banner;
use App\Models\CasePageSetting;
use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteFooterLink;
use App\Models\SiteNavMenu;
use App\Models\SitePartner;
use App\Models\SiteSetting;
use App\Models\SiteStatistic;
use Illuminate\Database\Seeder;

class HomeContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSections();
        $this->seedNavMenus();
        $this->seedSettings();
        $this->seedCategoriesAndProducts();
        $this->seedBanners();
        $this->seedCaseCms();
        $this->seedCases();
        $this->seedPartners();
        $this->seedStatistics();
        $this->seedNews();
        $this->seedAbout();
        $this->seedFooterLinks();
    }

    protected function seedSections(): void
    {
        HomeSection::ensureDefaults('zh-cn');
    }

    protected function seedNavMenus(): void
    {
        SiteNavMenu::ensureDefaults('zh-cn');
        SiteNavMenu::query()->where('menu_key', 'joinus')->update(['url' => '/join-us', 'is_active' => true]);
    }

    protected function seedSettings(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => '众鼠科技', 'group' => 'general', 'label' => '站点名称', 'type' => 'text'],
            ['key' => 'site_description', 'value' => '全场景智能清洁设备研发、制造、销售与服务。', 'group' => 'general', 'label' => '站点描述', 'type' => 'textarea'],
            ['key' => 'company_name', 'value' => '众鼠科技', 'group' => 'footer', 'label' => '公司名称', 'type' => 'text'],
            ['key' => 'footer_tagline', 'value' => '专注智能清洁设备与全场景环卫生态，为城市与产业空间提供可靠装备与数字化服务。', 'group' => 'footer', 'label' => '页脚简介', 'type' => 'textarea'],
            ['key' => 'contact_phone', 'value' => '15378711662', 'group' => 'footer', 'label' => '联系电话', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'zsmart@zsmartglobal.com', 'group' => 'footer', 'label' => '联系邮箱', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => '深圳市南山区粤海街道高新社区高新南九道61号卫星大厦906', 'group' => 'footer', 'label' => '联系地址', 'type' => 'textarea'],
            ['key' => 'footer_copyright', 'value' => '© 2026 众鼠科技有限公司. All rights reserved', 'group' => 'footer', 'label' => '版权信息', 'type' => 'text'],
            ['key' => 'icp_number', 'value' => '', 'group' => 'footer', 'label' => 'ICP备案号', 'type' => 'text'],
            ['key' => 'header_logo_default', 'value' => '/home-assets/69f053450ed94.png', 'group' => 'header', 'label' => '顶部Logo(透明底)', 'type' => 'text'],
            ['key' => 'header_logo_scrolled', 'value' => '/home-assets/69f026d932c50.png', 'group' => 'header', 'label' => '顶部Logo(滚动后)', 'type' => 'text'],
            ['key' => 'footer_logo', 'value' => '/home-assets/69e9d8260dd85.png', 'group' => 'footer', 'label' => '页脚Logo', 'type' => 'text'],
        ];

        foreach ($settings as $row) {
            SiteSetting::query()->updateOrCreate(['key' => $row['key']], $row);
        }
    }

    protected function seedCategoriesAndProducts(): void
    {
        $sanitation = Category::query()->updateOrCreate(
            ['slug' => 'sanitation-series'],
            ['name' => '环卫清扫系列', 'subtitle' => '市政道路清扫保洁设备', 'icon' => '/home-assets/69f047afcfe90.svg', 'cover_image' => '/home-assets/69eb54ef2b236.png', 'is_home_show' => true, 'is_home_featured' => true, 'sort_order' => 1, 'link' => '#home-products']
        );
        $property = Category::query()->updateOrCreate(
            ['slug' => 'property-series'],
            ['name' => '物业保洁系列', 'subtitle' => '物业场景清洁维护工具', 'icon' => '/home-assets/69f047c8d048a.svg', 'cover_image' => '/home-assets/69eb55482d236.png', 'is_home_show' => true, 'sort_order' => 2, 'link' => '#home-products']
        );
        $municipal = Category::query()->updateOrCreate(
            ['slug' => 'municipal-series'],
            ['name' => '市政系列', 'subtitle' => '市政道路养护作业设备', 'icon' => '/home-assets/69f047ec614dc.svg', 'cover_image' => '/home-assets/69eb554f8b0b3.png', 'is_home_show' => true, 'sort_order' => 3, 'link' => '#home-products']
        );

        $tabs = [
            [$sanitation, 'gaoya-qingxi-che', '高压清洗车', '路面高压冲洗清洁车辆', '/home-assets/69eb4b623908a.png', 'ZSKJ-GCJ-03', '/home-assets/69eb94fc8fbb7.png', true, '高压清洗车'],
            [$sanitation, 'huanwei-qingyun-xi', '环卫清运系', '垃圾收集转运专用设备', '/home-assets/69eb4b2422e95.png', null, '/home-assets/69ec8ce83fd56.png', true, '无人转运车'],
            [$sanitation, 'shuye-shouji-xi', '树叶收集系列', '道路落叶收集处理设备', '/home-assets/69eb4b1c28f39.png', null, '/home-assets/69eb964397e5f.png', true, '树叶收集设备'],
            [$property, 'saodi-ji-xi', '扫地机系列', '物业室内外地面清扫设备', '/home-assets/69eb4bb6535c6.png', null, '/home-assets/69eb963344077.png', true, '智能扫地机器人'],
            [$property, 'chentui-che-xi', '尘推车系列', '大面积地面除尘作业车', '/home-assets/69eb4c112db7c.png', null, '/home-assets/69eb961a9bdeb.png', true, '工业尘推车'],
        ];

        foreach ($tabs as $i => [$parent, $slug, $name, $subtitle, $cover, $model, $homeImage, $featured, $productName]) {
            $cat = Category::query()->updateOrCreate(
                ['slug' => $slug],
                ['parent_id' => $parent->id, 'name' => $name, 'subtitle' => $subtitle, 'cover_image' => $cover, 'is_station_tab' => true, 'sort_order' => $i + 1]
            );
            Product::query()->updateOrCreate(
                ['category_id' => $cat->id, 'name' => $productName],
                [
                    'model_no' => $model,
                    'subtitle' => $subtitle,
                    'home_image' => $homeImage,
                    'cover_image' => $cover,
                    'metrics' => [
                        ['value' => '1000mm', 'label' => '割草幅宽'],
                        ['value' => '20-200mm', 'label' => '割草高度'],
                    ],
                    'is_home_show' => true,
                    'is_home_featured' => $featured,
                    'is_active' => true,
                    'sort_order' => 1,
                    'detail_url' => null,
                ]
            );
        }

        $huanweiCat = Category::query()->where('slug', 'huanwei-qingyun-xi')->first();
        if ($huanweiCat) {
            Product::query()->updateOrCreate(
                ['category_id' => $huanweiCat->id, 'name' => '遥控割草机'],
                [
                    'model_no' => 'ZSKJ-GCJ-03',
                    'subtitle' => '遥控割草机',
                    'home_image' => '/home-assets/69eb951beb5c5.png',
                    'cover_image' => '/home-assets/69eb951beb5c5.png',
                    'is_home_show' => true,
                    'is_home_featured' => false,
                    'sort_order' => 2,
                    'is_active' => true,
                    'detail_url' => null,
                ]
            );
        }

        SiteNavMenu::query()->where('menu_key', 'product_mega')->update(['url' => '/products']);
    }

    protected function seedBanners(): void
    {
        $images = [
            ['/home-assets/6a0d244c86ac5.jpg', '了解产品', '#home-products'],
            ['/home-assets/6a0d243f03614.jpg', null, null],
            ['/home-assets/6a0d106b17b15.jpg', null, null],
            ['/home-assets/6a0d2459ebab5.jpg', null, null],
            ['/home-assets/6a0d0fd17d53c.jpg', '了解产品', '#home-products'],
        ];

        foreach ($images as $i => [$img, $btn, $link]) {
            Banner::query()->updateOrCreate(
                ['title' => '首页轮播 '.($i + 1), 'position' => 'home'],
                [
                    'subtitle' => null,
                    'image' => $img,
                    'image_mobile' => $img,
                    'button_text' => $btn,
                    'link' => $link,
                    'sort_order' => $i,
                    'is_active' => true,
                    'locale' => 'zh-cn',
                ]
            );
        }
    }

    protected function seedCaseCms(): void
    {
        CaseStudyCategory::ensureDefaults('zh-cn');
        CasePageSetting::forLocale('zh-cn');
    }

    protected function seedCases(): void
    {
        $categoryMap = CaseStudyCategory::query()
            ->forLocale('zh-cn')
            ->pluck('id', 'slug');

        $cases = [
            [
                'title' => '四川凉山州盐源苹果产业基地',
                'region' => '四川省 / 凉山彝族自治州 / 盐源县',
                'category' => 'industry-base',
                'cover' => '/home-assets/69feb8a375904.png',
                'featured' => true,
                'tags' => ['环卫清扫车'],
            ],
            [
                'title' => '广州增城荔枝产业基地',
                'region' => '广东省 / 广州市 / 增城区',
                'category' => 'industry-base',
                'cover' => '/home-assets/69feb91e9b36d.png',
                'featured' => true,
                'tags' => ['洗地机器人'],
            ],
            [
                'title' => '某智慧城市园区',
                'region' => '新疆维吾尔自治区 / 阿克苏地区',
                'category' => 'campus',
                'cover' => '/home-assets/69eb39ed040f4.jpg',
                'featured' => true,
                'tags' => ['园区保洁方案'],
            ],
            [
                'title' => '广西百色芒果产业基地',
                'region' => '广西壮族自治区 / 百色市',
                'category' => 'industry-base',
                'cover' => '/home-assets/69feb7e2e461a.jpg',
                'featured' => false,
                'tags' => [],
            ],
            [
                'title' => '河北秦皇岛樱桃产业基地',
                'region' => '河北省 / 秦皇岛市',
                'category' => 'industry-base',
                'cover' => '/home-assets/69feb9f9b7eb4.jpg',
                'featured' => false,
                'tags' => [],
            ],
        ];

        foreach ($cases as $i => $row) {
            CaseStudy::query()->updateOrCreate(
                ['title' => $row['title'], 'locale' => 'zh-cn'],
                [
                    'region' => $row['region'],
                    'scene_type' => '产业基地',
                    'category_id' => $categoryMap[$row['category']] ?? null,
                    'cover_image' => $row['cover'],
                    'excerpt' => '众鼠科技智能清洁设备在该场景的成功应用，助力客户提升环境品质与运营效率。',
                    'product_tags' => $row['tags'],
                    'is_home_show' => true,
                    'is_featured' => $row['featured'],
                    'sort_order' => $i,
                    'is_active' => true,
                    'locale' => 'zh-cn',
                    'published_at' => now(),
                ]
            );
        }
    }

    protected function seedPartners(): void
    {
        for ($i = 0; $i < 12; $i++) {
            SitePartner::query()->updateOrCreate(
                ['name' => '合作伙伴 '.($i + 1)],
                ['logo' => '/home-assets/69eb043d4bc2d.png', 'is_home_show' => true, 'sort_order' => $i, 'is_active' => true]
            );
        }
    }

    protected function seedStatistics(): void
    {
        SiteStatistic::ensureDefaults('zh-cn');
    }

    protected function seedNews(): void
    {
        ArticleCategory::ensureDefaults('zh-cn');

        $catExhibition = ArticleCategory::query()->where('slug', 'exhibition')->where('locale', 'zh-cn')->first();
        $catCompany = ArticleCategory::query()->where('slug', 'company')->where('locale', 'zh-cn')->first();
        $catIndustry = ArticleCategory::query()->where('slug', 'industry')->where('locale', 'zh-cn')->first();

        $items = [
            ['2025北京第25届环卫展及垃圾分类博览会', '2025年企发环卫展将于4月10-12日在北京·全国农业展览馆（新馆）举行。', '/home-assets/69eb3db6a3bc9.png', $catExhibition?->id, true],
            ['公司介绍', '众鼠科技是一家集智能清洁设备研发、生产、销售与服务为一体的高新技术企业。', '/home-assets/69eaf27c8cb03.jpg', $catCompany?->id, false],
            ['守护城市动脉的智慧之选——地铁站洗地机应用', '手推式洗地机以灵活高效的特点，成为地铁站等公共空间清洁维护的重要设备选择。', '/home-assets/69eb3d4e51dba.png', $catIndustry?->id, false],
        ];

        foreach ($items as $i => [$title, $summary, $cover, $categoryId, $featured]) {
            $article = Article::query()->firstOrNew(['title' => $title, 'locale' => 'zh-cn']);
            $article->fill([
                'category_id' => $categoryId,
                'summary' => $summary,
                'cover_image' => $cover,
                'is_published' => true,
                'is_featured' => $featured,
                'is_home_show' => true,
                'published_at' => now()->subDays($i),
                'sort_order' => $i,
            ]);
            $article->save();
        }
    }

    protected function seedAbout(): void
    {
        Page::query()->updateOrCreate(
            ['page_key' => 'about', 'locale' => 'zh-cn'],
            [
                'title' => '关于众鼠科技',
                'slug' => 'about',
                'subtitle' => '以智能清洁设备研发制造，推动城市空间服务升级。',
                'excerpt' => '众鼠科技是一家集研发、制造、销售与服务于一体的智能设备企业，围绕城市环卫、物业保洁、园区服务、商业空间等场景，提供智能化、数字化、全流程的清洁设备解决方案。',
                'cover_image' => '/home-assets/69e9ff102a425.jpg',
                'button_text' => '了解我们',
                'button_url' => '#',
                'is_published' => true,
            ]
        );
    }

    protected function seedFooterLinks(): void
    {
        $groups = [
            ['products', '产品中心', [['无人转运车', '#'], ['遥控割草机', '#']]],
            ['solutions', '解决方案', [['招商加盟', '/cases']]],
            ['about', '关于我们', [['公司介绍', '#home-about'], ['新闻资讯', '/news']]],
        ];

        foreach ($groups as [$key, $label, $links]) {
            foreach ($links as $i => [$text, $url]) {
                SiteFooterLink::query()->updateOrCreate(
                    ['group_key' => $key, 'label' => $text],
                    ['group_label' => $label, 'url' => $url, 'sort_order' => $i, 'is_active' => true]
                );
            }
        }
    }
}

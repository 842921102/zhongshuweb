<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable([
    'section_key', 'section_name', 'title', 'title_highlight', 'subtitle',
    'visual_image', 'visual_image_mobile', 'visual_text', 'visual_button_label', 'visual_button_url',
    'is_enabled', 'sort_order', 'background_color', 'locale',
])]
class HomeSection extends Model
{
    use HasLocale;

    /** @var array<string, string> section_key => 前台锚点 id */
    public const SECTION_ANCHORS = [
        'hero' => 'home-hero',
        'solutions' => 'home-solutions',
        'products' => 'home-products',
        'cases' => 'home-case',
        'partners' => 'home-partners',
        'news' => 'home-news',
        'about' => 'home-about',
    ];

    /** @var array<string, string> section_key => Blade 局部视图 */
    public const SECTION_PARTIALS = [
        'hero' => 'home.partials.hero',
        'solutions' => 'home.partials.solutions',
        'products' => 'home.partials.products',
        'cases' => 'home.partials.cases',
        'partners' => 'home.partials.partners',
        'news' => 'home.partials.news',
        'about' => 'home.partials.about',
    ];

    /** @var array<string, array{section_name: string, hint: string}> */
    public const DEFINITIONS = [
        'hero' => [
            'section_name' => '首屏 Banner',
            'hint' => '控制首屏轮播是否显示（轮播内容在「轮播图」中维护）',
        ],
        'solutions' => [
            'section_name' => '全场景解决方案',
            'hint' => '对应前台 #home-solutions，标题区文案',
        ],
        'products' => [
            'section_name' => '全系产品站',
            'hint' => '对应前台 #home-products',
        ],
        'cases' => [
            'section_name' => '项目案例',
            'hint' => '对应前台 #home-case',
        ],
        'partners' => [
            'section_name' => '合作伙伴与数据',
            'hint' => '对应前台 #home-partners',
        ],
        'news' => [
            'section_name' => '新闻资讯',
            'hint' => '对应前台 #home-news',
        ],
        'about' => [
            'section_name' => '关于我们',
            'hint' => '对应前台 #home-about；可在此单独配置大图与浮层文案',
        ],
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        $flush = static function (): void {
            foreach (['zh-cn', 'en-us'] as $locale) {
                Cache::forget("home_sections_{$locale}");
            }
        };

        static::saved($flush);
        static::deleted($flush);
    }

    public function displayTitle(): string
    {
        $title = trim((string) $this->title);
        $highlight = trim((string) $this->title_highlight);

        if ($title === '' && $highlight === '') {
            return '—';
        }

        return $highlight !== '' ? $title.$highlight : $title;
    }

    public function isHero(): bool
    {
        return $this->section_key === 'hero';
    }

    public function anchorId(): string
    {
        return self::SECTION_ANCHORS[$this->section_key] ?? 'home-'.$this->section_key;
    }

    public function partialView(): ?string
    {
        return self::SECTION_PARTIALS[$this->section_key] ?? null;
    }

    /**
     * @param  array<string, self>  $sections
     * @return list<self>
     */
    public static function sortedEnabled(array $sections): array
    {
        return collect($sections)
            ->sortBy(fn (self $section) => [$section->sort_order, $section->id])
            ->filter(fn (self $section) => $section->isEnabled())
            ->values()
            ->all();
    }

    /**
     * 首屏「向下滚动」目标：排序后紧跟 hero 的第一个已启用模块。
     *
     * @param  array<string, self>  $sections
     */
    public static function heroScrollTarget(array $sections): string
    {
        $ordered = self::sortedEnabled($sections);
        $next = collect($ordered)->first(fn (self $section) => $section->section_key !== 'hero');

        if ($next !== null) {
            return '#'.$next->anchorId();
        }

        return '#'.(self::SECTION_ANCHORS['about'] ?? 'home-about');
    }

    /**
     * @return array<string, self>
     */
    public static function mapForLocale(string $locale = 'zh-cn'): array
    {
        return static::query()
            ->forLocale($locale)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('section_key')
            ->all();
    }

    public function isEnabled(): bool
    {
        return (bool) $this->is_enabled;
    }

    public static function isEnabledIn(array $sections, string $key): bool
    {
        $section = $sections[$key] ?? null;

        if ($section === null) {
            return true;
        }

        if ($section instanceof self) {
            return $section->isEnabled();
        }

        return (bool) ($section['is_enabled'] ?? true);
    }

    public static function ensureDefaults(string $locale = 'zh-cn'): void
    {
        $defaults = [
            'hero' => [
                'section_name' => '首屏 Banner',
                'sort_order' => 1,
            ],
            'solutions' => [
                'section_name' => '全场景解决方案',
                'title' => '全场景智能清洁',
                'title_highlight' => '解决方案',
                'subtitle' => '从城市道路到室内空间，从垃圾收集到数据管理，提供完整的智能环卫生态系统。',
                'sort_order' => 2,
            ],
            'products' => [
                'section_name' => '全系产品站',
                'title' => '全系',
                'title_highlight' => '产品站',
                'subtitle' => '创新尖端科技，普惠智慧生活 / 家居智慧新高度 / 全场景智慧清洁方案',
                'sort_order' => 3,
            ],
            'cases' => [
                'section_name' => '项目案例',
                'title' => '全国7+',
                'title_highlight' => '项目成功落地',
                'subtitle' => '服务覆盖全国主要城市，赢得客户广泛认可与信赖。',
                'sort_order' => 4,
            ],
            'partners' => [
                'section_name' => '合作伙伴与数据',
                'title' => '携手',
                'title_highlight' => '行业伙伴',
                'subtitle' => '与多家企业和场景方建立合作，共同推动智能清洁设备落地应用。',
                'sort_order' => 5,
            ],
            'news' => [
                'section_name' => '新闻资讯',
                'title' => '了解',
                'title_highlight' => '最新动态',
                'subtitle' => '关注众鼠科技新闻资讯，了解智能清洁行业趋势。',
                'sort_order' => 6,
            ],
            'about' => [
                'section_name' => '关于我们',
                'title' => '关于',
                'title_highlight' => '众鼠科技',
                'subtitle' => '以智能清洁设备研发制造，推动城市空间服务升级。',
                'sort_order' => 7,
            ],
        ];

        foreach ($defaults as $key => $data) {
            static::query()->updateOrCreate(
                ['section_key' => $key, 'locale' => $locale],
                array_merge($data, [
                    'section_key' => $key,
                    'is_enabled' => true,
                    'locale' => $locale,
                ])
            );
        }
    }
}

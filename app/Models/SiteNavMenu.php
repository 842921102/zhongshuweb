<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

#[Fillable([
    'menu_key', 'parent_id', 'menu_type', 'label', 'url',
    'route_keys', 'search_keywords', 'open_in_new_tab',
    'sort_order', 'is_active', 'locale',
])]
class SiteNavMenu extends Model
{
    use HasLocale;

    public const TYPE_LINK = 'link';

    public const TYPE_PRODUCT_MEGA = 'product_mega';

    /** @var array<string, string> */
    public const TYPE_LABELS = [
        self::TYPE_LINK => '普通链接',
        self::TYPE_PRODUCT_MEGA => '产品下拉菜单',
    ];

    /** @var list<string> */
    public const SYSTEM_KEYS = [
        'home', 'product_mega', 'industry_cases', 'case_center', 'about', 'news', 'support', 'joinus',
    ];

    /** @var array<string, string> */
    public const SYSTEM_KEY_LABELS = [
        'home' => '首页 (home)',
        'product_mega' => '产品下拉 (product_mega)',
        'industry_cases' => '解决方案 /industry-cases (industry_cases)',
        'case_center' => '客户案例 /cases (case_center)',
        'about' => '关于我们页 (about)',
        'news' => '新闻 (news)',
        'support' => '技术支持 (support)',
        'joinus' => '加入我们 (joinus)',
    ];

    protected function casts(): array
    {
        return [
            'open_in_new_tab' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        $flush = static function (): void {
            foreach (['zh-cn', 'en-us'] as $locale) {
                Cache::forget("site_nav_menus_{$locale}");
            }
        };

        static::saved($flush);
        static::deleted($flush);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function isProductMega(): bool
    {
        return $this->menu_type === self::TYPE_PRODUCT_MEGA;
    }

    public function isSystem(): bool
    {
        return filled($this->menu_key) && in_array($this->menu_key, self::SYSTEM_KEYS, true);
    }

    public function href(): string
    {
        $url = trim((string) $this->url);

        if ($url === '' || $url === '/') {
            return localized_url('/', $this->locale);
        }

        if (str_starts_with($url, '#') || str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return localized_url($url, $this->locale);
    }

    /**
     * @return Collection<int, self>
     */
    public static function headerMenus(string $locale = 'zh-cn'): Collection
    {
        return static::query()
            ->forLocale($locale)
            ->roots()
            ->active()
            ->orderBy('sort_order')
            ->get();
    }

    public static function ensureDefaults(string $locale = 'zh-cn'): void
    {
        $defaults = [
            [
                'menu_key' => 'home',
                'menu_type' => self::TYPE_LINK,
                'label' => '首页',
                'url' => '/',
                'route_keys' => 'home,index',
                'search_keywords' => '首页 home,index',
                'sort_order' => 1,
            ],
            [
                'menu_key' => 'product_mega',
                'menu_type' => self::TYPE_PRODUCT_MEGA,
                'label' => '产品',
                'url' => '/products',
                'route_keys' => 'product',
                'search_keywords' => '产品 product',
                'sort_order' => 2,
            ],
            [
                'menu_key' => 'industry_cases',
                'menu_type' => self::TYPE_LINK,
                'label' => '解决方案',
                'url' => '/industry-cases',
                'route_keys' => 'industry,industry-cases',
                'search_keywords' => '解决方案 行业方案 industry cases solutions',
                'sort_order' => 3,
            ],
            [
                'menu_key' => 'case_center',
                'menu_type' => self::TYPE_LINK,
                'label' => '客户案例',
                'url' => '/cases',
                'route_keys' => 'case,cases',
                'search_keywords' => '客户案例 项目案例 cases',
                'sort_order' => 4,
            ],
            [
                'menu_key' => 'about',
                'menu_type' => self::TYPE_LINK,
                'label' => '关于我们',
                'url' => '/about',
                'route_keys' => 'about,culture',
                'search_keywords' => '关于我们 公司简介 企业文化 荣誉 about',
                'sort_order' => 5,
            ],
            [
                'menu_key' => 'news',
                'menu_type' => self::TYPE_LINK,
                'label' => '新闻资讯',
                'url' => '/news',
                'route_keys' => 'news',
                'search_keywords' => '新闻资讯 news',
                'sort_order' => 6,
            ],
            [
                'menu_key' => 'support',
                'menu_type' => self::TYPE_LINK,
                'label' => '技术支持',
                'url' => '/support',
                'route_keys' => 'support',
                'search_keywords' => '技术支持 support',
                'sort_order' => 7,
            ],
            [
                'menu_key' => 'joinus',
                'menu_type' => self::TYPE_LINK,
                'label' => '加入我们',
                'url' => '/join-us',
                'route_keys' => 'joinus',
                'search_keywords' => '加入我们 招聘 joinus',
                'sort_order' => 8,
            ],
        ];

        foreach ($defaults as $row) {
            static::query()->updateOrCreate(
                ['menu_key' => $row['menu_key'], 'locale' => $locale],
                array_merge($row, [
                    'parent_id' => null,
                    'open_in_new_tab' => false,
                    'is_active' => true,
                    'locale' => $locale,
                ])
            );
        }
    }
}

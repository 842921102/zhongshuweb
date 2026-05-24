<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use App\Services\ProductPageService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'meta_title', 'meta_description', 'meta_keywords',
    'banner_media_type', 'banner_image_pc', 'banner_image_mobile',
    'banner_video_url', 'banner_video_poster',
    'view_all_label', 'all_label', 'detail_label', 'catalog_empty',
    'detail_labels',
])]
class ProductPageSetting extends Model
{
    use HasLocale;

    public static function forLocale(string $locale = 'zh-cn'): self
    {
        return static::query()->firstOrCreate(
            ['locale' => $locale],
            static::defaultAttributes()
        );
    }

    protected function casts(): array
    {
        return [
            'detail_labels' => 'array',
        ];
    }

    public function isBannerVideo(): bool
    {
        return $this->banner_media_type === 'video' && filled($this->banner_video_url);
    }

    /** @return array<string, string|null> */
    public static function defaultAttributes(): array
    {
        return [
            'banner_media_type' => 'image',
            'meta_title' => '产品中心 - 众鼠智能',
            'meta_description' => '众鼠智能产品中心，覆盖环卫清扫、物业保洁、市政装备与机器人产品矩阵。',
            'meta_keywords' => '众鼠,产品中心,环卫清扫,物业保洁,市政装备,机器人',
            'view_all_label' => '查看全部',
            'all_label' => '全部',
            'detail_label' => '查看详情',
            'catalog_empty' => '暂无产品数据',
        ];
    }

    /** @return array<string, string> */
    public function mergedDetailLabels(): array
    {
        $defaults = ProductPageService::defaultDetailLabels();
        $custom = is_array($this->detail_labels) ? $this->detail_labels : [];

        return array_merge($defaults, array_filter($custom, fn ($v) => filled($v)));
    }

    /** 产品列表页文案 */
    public function listLabels(): array
    {
        $defaults = static::defaultAttributes();

        return [
            'all' => $this->all_label ?: $defaults['all_label'],
            'detail' => $this->detail_label ?: $defaults['detail_label'],
            'catalog_empty' => $this->catalog_empty ?: $defaults['catalog_empty'],
        ];
    }

    /** 顶栏产品下拉文案 */
    public function navLabels(): array
    {
        $defaults = static::defaultAttributes();

        return [
            'view_all' => $this->view_all_label ?: $defaults['view_all_label'],
            'catalog_empty' => $this->catalog_empty ?: $defaults['catalog_empty'],
        ];
    }
}

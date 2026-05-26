<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'page_title', 'page_subtitle',
    'banner_video_url', 'banner_image_pc', 'banner_image_mobile', 'banner_height',
    'detail_button_text', 'meta_title', 'meta_description',
])]
class IndustrySolutionPageSetting extends Model
{
    use HasLocale;

    public static function forLocale(string $locale = 'zh-cn'): self
    {
        return static::query()->firstOrCreate(
            ['locale' => $locale],
            [
                'page_title' => '解决方案',
                'page_subtitle' => '覆盖环卫市政、园区物业、商业空间等多行业的智能清洁应用实践',
                'detail_button_text' => '查看方案',
                'meta_title' => '解决方案 - 众鼠科技',
                'meta_description' => '众鼠科技解决方案：智能清洁装备在环卫、物业、园区、商业等场景的方案与落地实践。',
                'banner_height' => 640,
            ]
        );
    }
}

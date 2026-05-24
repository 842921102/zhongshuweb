<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'meta_title', 'meta_description', 'meta_keywords',
    'banner_image_pc', 'banner_image_mobile', 'banner_height', 'read_more_label', 'all_category_label',
])]
class NewsPageSetting extends Model
{
    use HasLocale;

    public static function forLocale(string $locale = 'zh-cn'): self
    {
        return static::query()->firstOrCreate(
            ['locale' => $locale],
            [
                'meta_title' => '新闻资讯 - 众鼠智能',
                'meta_description' => '新闻资讯 - 众鼠智能最新动态、产品发布与行业深度资讯',
                'meta_keywords' => '众鼠智能,新闻资讯,新能源商用车,环卫车辆,行业动态',
                'banner_height' => 450,
                'read_more_label' => '阅读全文',
                'all_category_label' => '全部',
            ]
        );
    }
}

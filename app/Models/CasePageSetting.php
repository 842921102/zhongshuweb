<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'page_title', 'page_subtitle', 'meta_title', 'meta_description',
])]
class CasePageSetting extends Model
{
    use HasLocale;

    public static function forLocale(string $locale = 'zh-cn'): self
    {
        return static::query()->firstOrCreate(
            ['locale' => $locale],
            [
                'page_title' => '客户案例',
                'page_subtitle' => '全场景智能清洁设备在各类场景的成功应用，以可靠装备与数字化服务助力客户提升运营效率。',
                'meta_title' => '客户案例 - 众鼠科技',
                'meta_description' => '众鼠科技客户案例：环卫清扫、产业园区、产业基地等场景的项目落地与实践。',
            ]
        );
    }
}

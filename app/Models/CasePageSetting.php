<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use App\Models\Concerns\RemembersLocaleRow;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'page_title', 'page_subtitle',
    'banner_image_pc', 'banner_image_mobile', 'banner_height',
    'meta_title', 'meta_description',
])]
class CasePageSetting extends Model
{
    use HasLocale;
    use RemembersLocaleRow;

    protected static function booted(): void
    {
        static::bootRemembersLocaleRow();
    }

    /** @return array<string, mixed> */
    public static function defaultAttributes(): array
    {
        return [
            'page_title' => '客户案例',
            'page_subtitle' => '全场景智能清洁设备在各类场景的成功应用，以可靠装备与数字化服务助力客户提升运营效率。',
            'meta_title' => '客户案例 - 众鼠科技',
            'meta_description' => '众鼠科技客户案例：环卫清扫、产业园区、产业基地等场景的项目落地与实践。',
            'banner_height' => 640,
        ];
    }
}

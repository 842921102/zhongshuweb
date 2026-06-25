<?php

namespace App\Models;

use App\Casts\JsonArrayCast;
use App\Models\Concerns\HasLocale;
use App\Models\Concerns\RemembersLocaleRow;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'meta_title', 'meta_description', 'meta_keywords',
    'hero_image_pc', 'hero_image_mobile', 'hero_height',
    'hero_eyebrow', 'hero_title', 'hero_subtitle',
    'docs_kicker', 'docs_title', 'videos_kicker', 'videos_title',
    'service_kicker', 'service_form_title',
    'contact_title', 'contact_phone_label', 'contact_phone',
    'contact_email_label', 'contact_email', 'contact_address_label', 'contact_address',
    'doc_categories', 'form_topics',
])]
class SupportPageSetting extends Model
{
    use HasLocale;
    use RemembersLocaleRow;

    protected static function booted(): void
    {
        static::bootRemembersLocaleRow();
    }

    protected function casts(): array
    {
        return [
            'doc_categories' => JsonArrayCast::class,
            'form_topics' => JsonArrayCast::class,
            'hero_height' => 'integer',
        ];
    }

    /** @return array<string, mixed> */
    public static function defaultAttributes(string $locale): array
    {
        return [
            'meta_title' => '技术支持 - 众鼠智能',
            'meta_description' => '众鼠智能技术支持页面，提供技术文档下载、教学视频与售后服务申请。',
            'meta_keywords' => '众鼠智能,技术支持,PDF文档,教学视频,售后服务',
            'hero_image_pc' => 'support/hero/support-hero-pc.jpg',
            'hero_image_mobile' => 'support/hero/support-hero-mobile.jpg',
            'hero_height' => 640,
            'hero_eyebrow' => null,
            'hero_title' => '技术支持中心',
            'hero_subtitle' => null,
            'docs_kicker' => 'Documents · 产品资料文档',
            'docs_title' => 'PDF 技术文档下载',
            'videos_kicker' => 'VIDEO TUTORIALS · 教学视频',
            'videos_title' => '视频教程中心',
            'service_kicker' => 'Submit Request',
            'service_form_title' => '提交售后服务申请',
            'contact_title' => "7×24 售后\n全程专人跟进",
            'contact_phone_label' => '全国热线',
            'contact_phone' => '15378711662',
            'contact_email_label' => '技术邮箱',
            'contact_email' => 'zsmart@zsmartglobal.com',
            'contact_address_label' => '总部地址',
            'contact_address' => '上海市浦东新区成山路718弄1号T1栋906室',
            'doc_categories' => ['产品手册', '产品介绍'],
            'form_topics' => ['产品使用咨询', '故障维修申请', '配件采购', '软件/固件问题', '其他'],
        ];
    }

    /** @return list<string> */
    public function categoryFilters(): array
    {
        $normalized = self::normalizeStringList($this->doc_categories);

        return $normalized !== [] ? $normalized : ['产品手册', '产品介绍'];
    }

    /** @return list<string> */
    public function topicOptions(): array
    {
        $normalized = self::normalizeStringList($this->form_topics);

        return $normalized !== [] ? $normalized : ['产品使用咨询', '故障维修申请', '配件采购', '软件/固件问题', '其他'];
    }

    /** @param  array<int|string, mixed>|null  $items */
    /** @return list<string> */
    private static function normalizeStringList(?array $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $out = [];

        foreach ($items as $item) {
            if (is_string($item) && $item !== '') {
                $out[] = $item;
            } elseif (is_array($item) && filled($item['value'] ?? null)) {
                $out[] = (string) $item['value'];
            }
        }

        return $out;
    }
}

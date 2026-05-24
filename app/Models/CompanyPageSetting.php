<?php

namespace App\Models;

use App\Casts\JsonArrayCast;
use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'meta_title', 'meta_description',
    'hero_media_url', 'hero_media_type', 'hero_poster_url',
    'intro_eyebrow', 'intro_title', 'intro_body',
    'intro_visual_title', 'intro_visual_text', 'intro_side_image',
    'global_metrics',
    'capabilities_eyebrow', 'capabilities_title', 'capabilities_lead', 'capabilities',
    'global_station_eyebrow', 'global_station_heading', 'service_stations',
    'timeline_eyebrow', 'timeline_title', 'timeline_lead',
    'culture_eyebrow', 'culture_title', 'culture_mission_text',
    'honors_eyebrow', 'honors_title', 'honors_subtitle',
    'team_eyebrow', 'team_title', 'team_tech_subtitle',
])]
class CompanyPageSetting extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'global_metrics' => JsonArrayCast::class,
            'capabilities' => JsonArrayCast::class,
            'service_stations' => JsonArrayCast::class,
        ];
    }

    /** @return list<array{icon: string, title: string, text: string}> */
    public function normalizedCapabilities(): array
    {
        $items = $this->capabilities;

        return ! empty($items) ? $items : \App\Support\CompanyAboutContent::capabilities();
    }

    /** @return list<array<string, mixed>> */
    public function normalizedServiceStations(): array
    {
        $stations = $this->service_stations;

        return ! empty($stations) ? $stations : static::defaultServiceStations();
    }

    public static function forLocale(string $locale = 'zh-cn'): self
    {
        return static::query()->firstOrCreate(
            ['locale' => $locale],
            static::defaultAttributes($locale)
        );
    }

    /** @return array<string, mixed> */
    public static function defaultAttributes(string $locale): array
    {
        return [
            'meta_title' => '关于我们 - 众鼠科技',
            'meta_description' => '众鼠科技是一家集智能清洁设备研发、生产、销售与服务为一体的高新技术企业，致力于全场景智能清洁解决方案。',
            'hero_media_type' => 'image',
            'hero_media_url' => '/home-assets/69e9ff102a425.jpg',
            'intro_eyebrow' => 'About Us',
            'intro_title' => '关于众鼠',
            'intro_body' => "众鼠科技是一家集研发、制造、销售与服务于一体的智能设备企业，围绕城市环卫、物业保洁、园区服务、商业空间等场景，提供智能化、数字化、全流程的清洁设备解决方案。\n\n我们以「让清洁更智能、让服务更高效」为愿景，持续投入产品研发与场景落地，助力客户降本增效、提升空间服务品质。",
            'intro_visual_title' => '从设备到运营',
            'intro_visual_text' => '让清洁设备真正服务于城市、园区、物业和商业空间的日常管理。',
            'intro_side_image' => null,
            'capabilities_eyebrow' => 'Core Capabilities',
            'capabilities_title' => '围绕客户真实场景，构建六大核心能力',
            'capabilities_lead' => '用产品能力解决作业问题，用服务能力保障项目落地，用数字化能力提升长期运营效率。',
            'capabilities' => \App\Support\CompanyAboutContent::capabilities(),
            'global_metrics' => [
                ['value' => '20+', 'label' => "服务城市"],
                ['value' => '500+', 'label' => "合作客户"],
                ['value' => '1000+', 'label' => "落地项目"],
            ],
            'global_station_eyebrow' => '· SERVICE STATION · 服务站',
            'global_station_heading' => '服务站 覆盖核心区域',
            'service_stations' => static::defaultServiceStations(),
            'timeline_eyebrow' => 'Development Path',
            'timeline_title' => '稳步建设产品、服务与项目交付体系',
            'timeline_lead' => '众鼠科技坚持以场景需求驱动产品，以客户价值检验能力，在长期项目落地中不断完善自身体系。',
            'culture_eyebrow' => '· Corporate Culture · 企业文化',
            'culture_title' => '企业文化',
            'culture_mission_text' => '以道德经智慧引领科技创新，在守正与创新、厚积与顺势之间，筑牢内核、应势致远。',
            'honors_eyebrow' => 'Honors & Certifications',
            'honors_title' => '品牌荣誉',
            'honors_subtitle' => '企业资质、行业奖牌与权威认证',
            'team_eyebrow' => 'Our Team',
            'team_title' => '团队介绍',
            'team_tech_subtitle' => '我们技术团队人员介绍',
        ];
    }

    /** @return list<array<string, string|null>> */
    public static function defaultServiceStations(): array
    {
        return [
            [
                'tab_label' => '上海服务站',
                'image' => '/home-assets/69e9ff102a425.jpg',
                'badge' => '高新技术企业',
                'title' => '众鼠上海站·总部标杆站',
                'description' => '集智能清洁设备研发、生产、销售服务为一体的高新技术企业',
                'phone' => '138 3718 5976',
            ],
            [
                'tab_label' => '商丘服务站',
                'image' => '/home-assets/69eaf27c8cb03.jpg',
                'badge' => '高新技术企业',
                'title' => '众鼠商丘站·区域服务中心',
                'description' => '面向华中区域提供智能清洁设备交付、运维与场景化解决方案支持',
                'phone' => '138 3718 5976',
            ],
        ];
    }
}

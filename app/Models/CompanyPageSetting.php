<?php

namespace App\Models;

use App\Casts\JsonArrayCast;
use App\Models\Concerns\HasLocale;
use App\Models\Concerns\RemembersLocaleRow;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'meta_title', 'meta_description',
    'hero_media_url', 'hero_media_mobile', 'hero_media_type', 'hero_poster_url', 'hero_poster_mobile',
    'banner_enabled',
    'intro_eyebrow', 'intro_title', 'intro_body',
    'intro_visual_title', 'intro_visual_text', 'intro_side_image', 'intro_side_image_mobile',
    'intro_enabled',
    'global_layout_enabled', 'global_layout_title',
    'global_layout_stats', 'global_layout_markers', 'global_layout_facilities', 'global_layout_map_image',
    'global_metrics',
    'capabilities_eyebrow', 'capabilities_title', 'capabilities_lead', 'capabilities',
    'capabilities_enabled',
    'global_station_eyebrow', 'global_station_heading', 'service_stations',
    'service_stations_enabled',
    'timeline_eyebrow', 'timeline_title', 'timeline_lead',
    'timeline_enabled',
    'culture_eyebrow', 'culture_title', 'culture_mission_text',
    'culture_enabled',
    'honors_eyebrow', 'honors_title', 'honors_subtitle',
    'honors_enabled',
    'team_eyebrow', 'team_title', 'team_tech_subtitle',
    'team_enabled',
])]
class CompanyPageSetting extends Model
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
            'banner_enabled' => 'boolean',
            'intro_enabled' => 'boolean',
            'global_layout_enabled' => 'boolean',
            'global_layout_stats' => JsonArrayCast::class,
            'global_layout_markers' => JsonArrayCast::class,
            'global_layout_facilities' => JsonArrayCast::class,
            'capabilities_enabled' => 'boolean',
            'service_stations_enabled' => 'boolean',
            'timeline_enabled' => 'boolean',
            'culture_enabled' => 'boolean',
            'honors_enabled' => 'boolean',
            'team_enabled' => 'boolean',
            'global_metrics' => JsonArrayCast::class,
            'capabilities' => JsonArrayCast::class,
            'service_stations' => JsonArrayCast::class,
        ];
    }

    public function showsBanner(): bool
    {
        return (bool) ($this->banner_enabled ?? true);
    }

    public function showsIntro(): bool
    {
        return (bool) ($this->intro_enabled ?? true);
    }

    public function showsGlobalLayout(): bool
    {
        return (bool) ($this->global_layout_enabled ?? true);
    }

    public function showsCapabilities(): bool
    {
        return (bool) ($this->capabilities_enabled ?? true);
    }

    /** @return array{title: string, stats: list<array{value: string, label: string}>, markers: list<array<string, mixed>>, facilities: list<array<string, string>>} */
    public function normalizedGlobalLayout(): array
    {
        $defaults = \App\Support\CompanyAboutContent::globalLayout();

        return [
            'title' => filled($this->global_layout_title) ? (string) $this->global_layout_title : $defaults['title'],
            'stats' => ! empty($this->global_layout_stats) ? $this->global_layout_stats : $defaults['stats'],
            'markers' => ! empty($this->global_layout_markers) ? $this->global_layout_markers : $defaults['markers'],
            'facilities' => ! empty($this->global_layout_facilities) ? $this->global_layout_facilities : $defaults['facilities'],
            'map_image' => $this->global_layout_map_image,
        ];
    }

    public function showsServiceStations(): bool
    {
        return (bool) ($this->service_stations_enabled ?? true);
    }

    public function showsTimeline(): bool
    {
        return (bool) ($this->timeline_enabled ?? true);
    }

    public function showsCulture(): bool
    {
        return (bool) ($this->culture_enabled ?? true);
    }

    public function showsHonors(): bool
    {
        return (bool) ($this->honors_enabled ?? true);
    }

    public function showsTeam(): bool
    {
        return (bool) ($this->team_enabled ?? true);
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
            'banner_enabled' => true,
            'intro_enabled' => true,
            'global_layout_enabled' => true,
            'global_layout_title' => '全球化布局',
            'global_layout_stats' => \App\Support\CompanyAboutContent::globalLayout()['stats'],
            'global_layout_markers' => \App\Support\CompanyAboutContent::globalLayout()['markers'],
            'global_layout_facilities' => \App\Support\CompanyAboutContent::globalLayout()['facilities'],
            'global_layout_map_image' => null,
            'capabilities_enabled' => true,
            'service_stations_enabled' => true,
            'timeline_enabled' => true,
            'culture_enabled' => true,
            'honors_enabled' => true,
            'team_enabled' => true,
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

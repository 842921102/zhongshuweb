<?php

namespace App\Support;

final class CompanyAboutContent
{
    /** @return list<array{icon: string, title: string, text: string}> */
    public static function capabilities(): array
    {
        return [
            ['icon' => '研', 'title' => '研发与产品能力', 'text' => '围绕清扫、清运、洗地、吸尘、市政养护等场景，持续完善产品矩阵，提升设备适配性与作业稳定性。'],
            ['icon' => '造', 'title' => '制造与供应链能力', 'text' => '整合设备制造、核心配件、质量控制和交付管理，保障产品供给效率与项目落地速度。'],
            ['icon' => '服', 'title' => '服务与运维能力', 'text' => '建立从售前咨询、方案设计、设备交付、培训使用到售后维护的服务闭环，降低客户使用门槛。'],
            ['icon' => '数', 'title' => '数据与管理能力', 'text' => '通过设备数据、作业记录和运维信息沉淀，辅助客户进行效率分析、资产管理和运营决策。'],
            ['icon' => '场', 'title' => '场景化方案能力', 'text' => '针对城市道路、园区物业、机场、工厂、产业基地等不同场景，提供差异化设备组合与实施方案。'],
            ['icon' => '合', 'title' => '生态合作能力', 'text' => '与渠道伙伴、项目方、服务商共同构建智能清洁生态，推动设备应用从单点采购走向规模化运营。'],
        ];
    }

    /** @return array{title: string, stats: list<array{value: string, label: string}>, markers: list<array<string, mixed>>, facilities: list<array<string, string>>} */
    public static function globalLayout(): array
    {
        return [
            'title' => '全球化布局',
            'stats' => [
                ['value' => '600+', 'label' => '全球服务网点'],
                ['value' => '80+', 'label' => '国家和地区'],
                ['value' => '1000+', 'label' => '覆盖城市'],
            ],
            'markers' => [
                ['name' => '美国', 'subtitle' => '海外子公司', 'lat' => 38.5, 'lon' => -98, 'label_side' => 'left'],
                ['name' => '荷兰', 'subtitle' => '海外子公司', 'lat' => 52.1, 'lon' => 5.3, 'label_side' => 'left'],
                ['name' => '中国', 'subtitle' => '总部 / 研发中心 / 工厂', 'lat' => 35, 'lon' => 105, 'label_side' => 'right'],
                ['name' => '日本', 'subtitle' => '海外子公司', 'lat' => 36.2, 'lon' => 138.3, 'label_side' => 'right'],
                ['name' => '韩国', 'subtitle' => '海外子公司', 'lat' => 37.5, 'lon' => 127, 'label_side' => 'left'],
                ['name' => '新加坡', 'subtitle' => '海外子公司', 'lat' => 1.35, 'lon' => 103.8, 'label_side' => 'right'],
            ],
            'facilities' => [
                ['value' => '2', 'title' => '全球总部', 'locations' => '深圳、香港'],
                ['value' => '3', 'title' => '研发中心', 'locations' => '深圳、成都、香港'],
                ['value' => '5', 'title' => '海外子公司', 'locations' => '日本、韩国、新加坡、美国、荷兰'],
                ['value' => '3', 'title' => '制造工厂', 'locations' => '华东、华中、华南核心基地'],
            ],
        ];
    }
}

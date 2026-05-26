<?php

namespace Database\Seeders;

use App\Models\IndustrySolution;
use App\Models\IndustrySolutionPageSetting;
use Illuminate\Database\Seeder;

class IndustrySolutionSeeder extends Seeder
{
    public function run(): void
    {
        IndustrySolutionPageSetting::forLocale('zh-cn');

        $items = [
            [
                'title' => '环卫市政',
                'slug' => 'municipal-sanitation',
                'sort_order' => 1,
                'excerpt' => '面向城市道路、广场与市政公共区域的智能清扫方案，提升作业效率，降低人工强度与安全风险。',
                'summary' => '众鼠科技环卫机器人可应用于道路洗扫、广场保洁、垃圾转运辅助等多个场景，帮助市政单位应对人工成本高、作业强度大、清洁标准难统一等行业痛点，提升环卫作业效率与城市管理品质。',
                'detail_data' => [
                    'stats' => [
                        'title' => '众鼠科技赋能环卫市政行业效率升级',
                        'footnote' => '*数据来源于众鼠智慧环卫平台',
                        'items' => [
                            ['label' => '清扫效率提升', 'value' => '2-3倍'],
                            ['label' => '人工强度降低', 'value' => '50%+'],
                            ['label' => '作业覆盖率', 'value' => '99%'],
                            ['label' => '综合运营成本', 'value' => '-30%'],
                        ],
                    ],
                    'coverage' => [
                        'title' => '全环节覆盖',
                        'subtitle' => '道路巡检 - 干扫除尘 - 洗扫保洁 - 广场清洁 - 夜间作业 - 数据上报',
                    ],
                    'scenes' => [
                        [
                            'title' => '道路洗扫保洁',
                            'slides' => [],
                            'challenge' => "• 作业面积大、班次多\n\n• 扬尘与重污路段清洁难度大\n\n• 高温雨雪等恶劣天气人力紧张\n\n• 作业质量难以量化考核",
                            'advantage' => "• 洗扫推吸一体，适应多种路面\n\n• 自动规划路径，支持定时任务\n\n• 降低一线人员重复劳动\n\n• 作业数据可视化，便于考核管理",
                            'products' => [
                                [
                                    'title' => '大型洗扫机器人',
                                    'bullets' => "• 大面积自动洗扫推吸\n• 支持自动回充与加水\n• 多传感器融合避障\n• 云端任务调度",
                                    'url' => '/products',
                                    'link_text' => '了解更多',
                                ],
                            ],
                        ],
                        [
                            'title' => '广场及人行道保洁',
                            'slides' => [],
                            'challenge' => "• 人流密集区域作业窗口短\n\n• 边角、台阶等区域难覆盖\n\n• 清洁频次高、耗材成本压力大",
                            'advantage' => "• 灵活部署，适应复杂路网\n\n• 低噪音设计，适合日间作业\n\n• 多种清洁模组可快速切换\n\n• 运维简单，上手快",
                            'products' => [],
                        ],
                    ],
                ],
            ],
            [
                'title' => '产业园区',
                'slug' => 'industrial-park',
                'sort_order' => 2,
                'excerpt' => '覆盖园区道路、厂房外围与公共通道的洗扫一体与巡检清洁，助力园区标准化运维。',
            ],
            [
                'title' => '商业地产',
                'slug' => 'commercial-property',
                'sort_order' => 3,
                'excerpt' => '商场、写字楼等商业空间的地面清洁与形象提升，兼顾高峰人流与高品质环境要求。',
            ],
            [
                'title' => '物业保洁',
                'slug' => 'property-services',
                'sort_order' => 4,
                'excerpt' => '住宅小区与综合体物业的日常保洁自动化，减轻重复劳动，提升服务响应与品质。',
            ],
            [
                'title' => '工业及仓储',
                'slug' => 'industrial-warehouse',
                'sort_order' => 5,
                'excerpt' => '工厂车间、仓库通道的大面积地面清洁，适应重污、高频与多班次作业场景。',
            ],
            [
                'title' => '交通枢纽',
                'slug' => 'transportation-hub',
                'sort_order' => 6,
                'excerpt' => '机场、高铁站、地铁站等枢纽站点的地面保洁与形象管理，保障大客流下的卫生标准。',
            ],
            [
                'title' => '医疗机构',
                'slug' => 'healthcare',
                'sort_order' => 7,
                'excerpt' => '医院门诊、住院部等区域的规范清洁，降低交叉感染风险，缓解医护后勤压力。',
            ],
            [
                'title' => '教育机构',
                'slug' => 'education',
                'sort_order' => 8,
                'excerpt' => '校园教学楼、图书馆、体育馆等场景的高效保洁，打造安全卫生的教学环境。',
            ],
            [
                'title' => '公共服务',
                'slug' => 'public-service',
                'sort_order' => 9,
                'excerpt' => '博物馆、展馆、政务大厅等公共场所的智慧清洁与接待形象升级。',
            ],
        ];

        foreach ($items as $row) {
            IndustrySolution::query()->updateOrCreate(
                ['locale' => 'zh-cn', 'slug' => $row['slug']],
                array_merge($row, [
                    'locale' => 'zh-cn',
                    'is_active' => true,
                    'published_at' => now(),
                ])
            );
        }
    }
}

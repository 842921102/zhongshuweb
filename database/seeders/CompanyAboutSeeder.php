<?php

namespace Database\Seeders;

use App\Models\CompanyCultureValue;
use App\Models\CompanyHonor;
use App\Models\CompanyMilestone;
use App\Models\CompanyPageSetting;
use App\Models\CompanyTeamMember;
use Illuminate\Database\Seeder;

class CompanyAboutSeeder extends Seeder
{
    public function run(): void
    {
        $locale = 'zh-cn';

        CompanyPageSetting::query()->updateOrCreate(
            ['locale' => $locale],
            CompanyPageSetting::defaultAttributes($locale)
        );

        $milestones = [
            [2026, '1月', '新一代智能洗地平台完成量产导入', null],
            [2025, '6月', '众鼠科技智慧清洁解决方案入选行业示范案例', '/home-assets/69eaf27c8cb03.jpg'],
            [2025, '3月', '多款核心产品通过整机可靠性验证并批量交付', null],
            [2024, '9月', '发布全场景智能清洁设备组合方案', '/home-assets/69eb3d4e51dba.png'],
            [2024, '5月', '拓展华东、华南区域服务网络', null],
            [2023, '11月', '获评高新技术企业', null],
            [2022, '8月', '首个产业园区标杆项目落地', '/home-assets/69eb3db6a3bc9.png'],
            [2021, '3月', '众鼠科技正式成立，聚焦智能清洁装备研发', '/home-assets/69e9ff102a425.jpg'],
        ];

        foreach ($milestones as $i => [$year, $month, $title, $image]) {
            CompanyMilestone::query()->updateOrCreate(
                [
                    'locale' => $locale,
                    'year' => $year,
                    'title' => $title,
                ],
                [
                    'month_label' => $month,
                    'image' => $image,
                    'sort_order' => $i,
                    'is_active' => true,
                ]
            );
        }

        $cultureItems = [
            [
                'label' => '重本',
                'subtitle' => '深耕根基，筑牢内核',
                'essence' => '坚守「合抱之木，生于毫末」的积累之道，以基础研究、核心技术与人才梯队为根本，不逐表面浮华，厚植企业长远发展的根基。',
                'practice' => null,
            ],
            [
                'label' => '顺道',
                'subtitle' => '循理而为，应势致远',
                'essence' => '以「道法自然」为准则，顺应技术发展规律、市场演变趋势与产业生态逻辑，不妄为、不逆势，让创新与商业同频共振。',
                'practice' => null,
            ],
            [
                'label' => '贵藏',
                'subtitle' => '藏锋守拙，严守核心',
                'essence' => '秉持「国之利器不可以示人」的审慎，珍视核心技术与创新成果，藏锋芒于务实，守机密于严谨，筑牢技术安全防线。',
                'practice' => null,
            ],
            [
                'label' => '守柔',
                'subtitle' => '以柔克刚，灵动应变',
                'essence' => '践行「天下之至柔，驰骋天下之至坚」的智慧，以柔性思维应对技术迭代，靠灵动适配突破行业壁垒，不逞刚猛、不陷固化。',
                'practice' => '布局柔性电子、AI 自适应系统等「至柔」技术，以敏捷研发响应市场变化，用轻量化架构实现高效迭代。',
            ],
            [
                'label' => '积微',
                'subtitle' => '见微知著，聚沙成塔',
                'essence' => '遵循「履霜坚冰，驯致其道」的逻辑，重视每一个技术细节、每一次小步迭代，以微创新积累大突破，靠持续精进成就领先。',
                'practice' => '推行精益研发模式，关注用户反馈中的细微需求，通过千万次算法优化、毫米级产品迭代，实现从量变到质变的跨越。',
            ],
        ];

        CompanyCultureValue::query()->where('locale', $locale)->delete();

        foreach ($cultureItems as $i => $item) {
            CompanyCultureValue::query()->create([
                'locale' => $locale,
                'label' => $item['label'],
                'subtitle' => $item['subtitle'],
                'essence' => $item['essence'],
                'practice' => $item['practice'],
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $honors = [
            ['高新技术企业', 'qualification', '/home-assets/69eb3d4e51dba.png'],
            ['ISO 质量管理体系认证', 'certificate', '/home-assets/69eb3db6a3bc9.png'],
            ['发明专利授权证书', 'patent', '/home-assets/69eaf27c8cb03.jpg'],
            ['智能清洁装备行业创新奖', 'award', '/home-assets/69e9ff102a425.jpg'],
            ['优秀解决方案服务商', 'award', '/home-assets/69eb54ef2b236.png'],
            ['产学研合作示范单位', 'qualification', '/home-assets/69eb55482d236.png'],
        ];

        CompanyHonor::query()->where('locale', $locale)->delete();

        foreach ($honors as $i => [$title, $category, $image]) {
            CompanyHonor::query()->create([
                'locale' => $locale,
                'title' => $title,
                'category' => $category,
                'image' => $image,
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        CompanyTeamMember::query()->updateOrCreate(
            ['locale' => $locale, 'name' => '张明远'],
            [
                'role' => '创始人 · 首席执行官',
                'bio' => '深耕智能清洁装备与机器人领域十余年，主导众鼠科技产品战略与核心技术路线，推动公司从研发创新到规模化项目交付的完整能力建设。',
                'photo' => null,
                'is_featured' => true,
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        $techTeam = [
            ['李博文', '首席技术官', '负责整体技术架构与自动驾驶、感知融合等核心算法研发，带领团队完成多款量产平台技术攻关。'],
            ['王雅琪', '硬件研发总监', '专注整机结构、电控系统与可靠性设计，保障产品在复杂场景下的稳定运行。'],
            ['陈浩然', '软件平台负责人', '主导设备云平台、运维管理系统与数据看板建设，提升客户运营数字化能力。'],
            ['赵思涵', '算法工程师', '从事路径规划、多机协同与场景适配算法研究，支撑产品智能化水平持续提升。'],
        ];

        foreach ($techTeam as $i => [$name, $role, $bio]) {
            CompanyTeamMember::query()->updateOrCreate(
                ['locale' => $locale, 'name' => $name],
                [
                    'role' => $role,
                    'bio' => $bio,
                    'photo' => null,
                    'is_featured' => false,
                    'sort_order' => $i,
                    'is_active' => true,
                ]
            );
        }
    }
}

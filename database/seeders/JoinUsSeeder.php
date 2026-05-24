<?php

namespace Database\Seeders;

use App\Models\JoinCultureCard;
use App\Models\JoinJobCategory;
use App\Models\JoinPageSetting;
use App\Models\JoinPosition;
use App\Models\JoinProcessStep;
use App\Models\JoinWelfareCard;
use App\Models\JoinWhyCard;
use Illuminate\Database\Seeder;

class JoinUsSeeder extends Seeder
{
    public function run(): void
    {
        $locale = 'zh-cn';
        JoinPageSetting::forLocale($locale);

        $categories = [
            ['name' => '销售商务', 'slug' => 'sales'],
            ['name' => '产品运营', 'slug' => 'product-ops'],
            ['name' => '项目交付', 'slug' => 'delivery'],
            ['name' => '售后服务', 'slug' => 'service'],
            ['name' => '技术研发', 'slug' => 'rnd'],
        ];

        foreach ($categories as $i => $row) {
            JoinJobCategory::query()->updateOrCreate(
                ['slug' => $row['slug'], 'locale' => $locale],
                array_merge($row, ['sort_order' => $i, 'is_active' => true])
            );
        }

        $cat = fn (string $slug) => JoinJobCategory::query()->where('slug', $slug)->where('locale', $locale)->value('id');

        $why = [
            ['业', '行业空间明确', '智能清洁设备正在进入环卫、物业、园区、商业空间等高频刚需场景，行业有长期发展机会。'],
            ['实', '业务足够真实', '我们面对的是设备选型、客户需求、项目交付、售后运维等真实问题，工作结果看得见。'],
            ['长', '能力长期成长', '你可以接触产品、渠道、项目、运营、客户现场等多个环节，形成复合型业务能力。'],
            ['共', '一起建设体系', '公司仍处于体系完善阶段，加入的人不仅执行任务，也能参与流程、标准和方法的建设。'],
        ];
        foreach ($why as $i => [$icon, $title, $desc]) {
            JoinWhyCard::query()->updateOrCreate(
                ['title' => $title, 'locale' => $locale],
                ['icon_char' => $icon, 'description' => $desc, 'sort_order' => $i, 'is_active' => true]
            );
        }

        $culture = [
            ['01 / 面向现场', '离客户近一点，离问题近一点', '设备能不能用、方案好不好、客户愿不愿意买单，答案往往不在办公室，而在真实场景里。'],
            ['02 / 重视交付', '把事情做完，比把话说满更重要', '我们希望每个人都能对结果负责，把需求、计划、执行、反馈形成闭环。'],
            ['03 / 持续迭代', '先跑起来，再不断优化', '面对快速变化的项目和客户需求，我们鼓励快速验证、及时复盘、持续改进。'],
        ];
        foreach ($culture as $i => [$step, $title, $desc]) {
            JoinCultureCard::query()->updateOrCreate(
                ['title' => $title, 'locale' => $locale],
                ['step_label' => $step, 'description' => $desc, 'sort_order' => $i, 'is_active' => true]
            );
        }

        $jobs = [
            ['sales', '解决方案销售经理', '销售商务', '上海 / 深圳', '全职', '经验 3 年以上', '负责智能清洁设备及行业解决方案的客户开发、需求沟通、方案推进和项目成交，面向环卫、物业、园区、商业空间等客户场景。', ['客户开发', '方案销售', '项目跟进', '渠道合作']],
            ['product-ops', '产品运营专员', '产品运营', '上海', '全职', '经验 1-3 年', '负责产品资料整理、官网内容维护、案例沉淀、解决方案包装、销售工具支持，协助产品和业务团队提升转化效率。', ['内容运营', '产品资料', '案例沉淀', '官网维护']],
            ['delivery', '项目交付经理', '项目交付', '全国项目', '全职', '经验 3 年以上', '负责客户现场调研、设备交付、使用培训、项目验收和问题协调，推动设备从交付到稳定使用的全过程落地。', ['现场调研', '设备交付', '客户培训', '项目验收']],
            ['service', '售后服务工程师', '售后服务', '上海 / 全国', '全职', '经验 1 年以上', '负责智能清洁设备的安装调试、故障处理、客户培训、巡检维护和售后问题记录，保障客户长期稳定使用。', ['设备调试', '故障处理', '客户培训', '售后巡检']],
        ];
        foreach ($jobs as $i => [$slug, $title, $dept, $loc, $type, $exp, $summary, $tags]) {
            JoinPosition::query()->updateOrCreate(
                ['title' => $title, 'locale' => $locale],
                [
                    'category_id' => $cat($slug),
                    'department_label' => $dept,
                    'location' => $loc,
                    'employment_type' => $type,
                    'experience' => $exp,
                    'summary' => $summary,
                    'tags' => $tags,
                    'sort_order' => $i,
                    'is_active' => true,
                ]
            );
        }

        $process = [
            ['投递简历', '通过邮箱、官网表单或招聘渠道投递简历，并注明意向岗位。'],
            ['简历筛选', 'HR 或业务负责人会根据岗位要求进行初步筛选。'],
            ['初步沟通', '了解个人经历、岗位匹配度、职业规划和到岗时间。'],
            ['业务面试', '围绕岗位能力、项目经验、问题解决能力进行深入沟通。'],
            ['Offer 沟通', '确认薪资、职责、入职时间和后续工作安排。'],
        ];
        foreach ($process as $i => [$title, $desc]) {
            JoinProcessStep::query()->updateOrCreate(
                ['title' => $title, 'locale' => $locale],
                ['description' => $desc, 'sort_order' => $i, 'is_active' => true]
            );
        }

        $welfare = [
            ['有竞争力的薪酬', '根据岗位价值、个人能力和项目贡献，提供匹配的薪酬与激励机制。'],
            ['清晰的成长空间', '参与从产品、销售、交付到售后的完整业务链路，提升综合能力。'],
            ['真实项目锻炼', '深入客户现场和项目场景，在真实问题中提升判断力和执行力。'],
            ['团队协同氛围', '倡导直接沟通、问题导向、快速反馈，减少无效内耗。'],
            ['培训与学习支持', '提供产品知识、设备使用、行业方案和客户沟通等方面的持续学习。'],
            ['长期事业机会', '伴随智能清洁行业发展，一起建设更完整的产品和服务体系。'],
        ];
        foreach ($welfare as $i => [$title, $desc]) {
            JoinWelfareCard::query()->updateOrCreate(
                ['title' => $title, 'locale' => $locale],
                ['description' => $desc, 'sort_order' => $i, 'is_active' => true]
            );
        }
    }
}

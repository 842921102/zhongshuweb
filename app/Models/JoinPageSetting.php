<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'locale', 'meta_title', 'meta_description', 'meta_keywords',
    'hero_image', 'hero_image_mobile', 'culture_image', 'culture_image_mobile',
    'hero_eyebrow', 'hero_title', 'hero_title_highlight', 'hero_description',
    'hero_cta_primary', 'hero_cta_secondary',
    'why_kicker', 'why_title', 'why_subtitle',
    'culture_kicker', 'culture_title', 'culture_subtitle',
    'jobs_kicker', 'jobs_title', 'jobs_subtitle', 'all_jobs_label',
    'process_kicker', 'process_title', 'process_subtitle',
    'welfare_kicker', 'welfare_title', 'welfare_subtitle',
    'contact_kicker', 'contact_title', 'contact_subtitle',
    'contact_email', 'contact_phone', 'contact_locations', 'contact_email_subject_tip',
    'apply_label', 'send_resume_label',
    'form_title', 'form_submit_label', 'form_success_message', 'form_error_message',
])]
class JoinPageSetting extends Model
{
    use HasLocale;

    public static function forLocale(string $locale = 'zh-cn'): self
    {
        return static::query()->firstOrCreate(
            ['locale' => $locale],
            static::defaultAttributes()
        );
    }

    /** @return array<string, mixed> */
    public static function defaultAttributes(): array
    {
        return [
            'meta_title' => '加入我们 - 众鼠科技',
            'meta_description' => '加入众鼠科技，与务实团队一起推动智能清洁行业升级。查看开放岗位、招聘流程与福利支持。',
            'meta_keywords' => '众鼠科技,招聘,加入我们,智能清洁,环卫设备',
            'hero_eyebrow' => 'JOIN ZHONGSHU TECHNOLOGY',
            'hero_title' => '和一群务实的人，一起推动',
            'hero_title_highlight' => '智能清洁行业升级',
            'hero_description' => '众鼠科技正在围绕智能清洁设备、城市环卫、物业保洁、市政养护和场景化项目交付，建设更完整的产品、服务与运营体系。我们欢迎认真做事、愿意深入现场、能够解决真实问题的人加入。',
            'hero_cta_primary' => '查看开放岗位',
            'hero_cta_secondary' => '投递简历',
            'why_kicker' => 'Why Join Us',
            'why_title' => '为什么加入众鼠科技',
            'why_subtitle' => '这不是一个只做官网展示的公司，而是一个围绕真实项目、真实设备、真实客户持续交付价值的团队。',
            'culture_kicker' => 'Culture',
            'culture_title' => '我们喜欢这样的工作方式',
            'culture_subtitle' => '不追求虚浮概念，更重视现场、结果、效率和长期责任。',
            'jobs_kicker' => 'Open Positions',
            'jobs_title' => '开放岗位',
            'jobs_subtitle' => '以下岗位由后台招聘管理维护，可配置部门、地点、职责与任职要求。',
            'all_jobs_label' => '全部岗位',
            'process_kicker' => 'Recruitment Process',
            'process_title' => '招聘流程',
            'process_subtitle' => '我们希望流程清楚、反馈及时，让彼此都能更高效地判断是否适合。',
            'welfare_kicker' => 'Benefits',
            'welfare_title' => '福利与成长支持',
            'welfare_subtitle' => '福利可按公司实际情况调整，以下为标准表达。',
            'contact_kicker' => 'Apply Now',
            'contact_title' => '把简历发给我们，一起做真实有价值的事情',
            'contact_subtitle' => '请将简历发送至邮箱，邮件标题建议为：“应聘岗位 + 姓名 + 城市”。如果你暂时没有看到完全匹配的岗位，也可以发送自荐简历。',
            'contact_email' => 'zsmart@zsmartglobal.com',
            'contact_phone' => '15378711662',
            'contact_locations' => '上海 / 深圳 / 全国项目现场',
            'contact_email_subject_tip' => '应聘岗位 + 姓名 + 城市',
            'apply_label' => '立即投递',
            'send_resume_label' => '发送简历',
            'form_title' => '在线投递简历',
            'form_submit_label' => '提交简历',
            'form_success_message' => '简历已提交，我们会尽快与您联系。',
            'form_error_message' => '提交失败，请稍后重试或发送邮件投递。',
        ];
    }
}

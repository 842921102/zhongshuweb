<?php

use App\Models\CompanyPageSetting;
use App\Support\CompanyAboutContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->string('intro_eyebrow', 80)->nullable()->after('intro_title');
            $table->string('intro_visual_title', 120)->nullable()->after('intro_body');
            $table->text('intro_visual_text')->nullable()->after('intro_visual_title');
            $table->string('intro_side_image', 500)->nullable()->after('intro_visual_text');

            $table->string('capabilities_eyebrow', 120)->nullable()->after('intro_side_image');
            $table->string('capabilities_title', 200)->nullable()->after('capabilities_eyebrow');
            $table->text('capabilities_lead')->nullable()->after('capabilities_title');
            $table->json('capabilities')->nullable()->after('capabilities_lead');

            $table->string('timeline_eyebrow', 120)->nullable()->after('timeline_title');
            $table->text('timeline_lead')->nullable()->after('timeline_eyebrow');

            $table->string('culture_eyebrow', 120)->nullable()->after('culture_title');

            $table->string('honors_eyebrow', 120)->nullable()->after('honors_title');

            $table->string('team_eyebrow', 80)->nullable()->after('team_title');
        });

        foreach (CompanyPageSetting::query()->cursor() as $setting) {
            $setting->forceFill([
                'intro_eyebrow' => $setting->intro_eyebrow ?: 'About Us',
                'intro_visual_title' => $setting->intro_visual_title ?: '从设备到运营',
                'intro_visual_text' => $setting->intro_visual_text ?: '让清洁设备真正服务于城市、园区、物业和商业空间的日常管理。',
                'capabilities_eyebrow' => $setting->capabilities_eyebrow ?: 'Core Capabilities',
                'capabilities_title' => $setting->capabilities_title ?: '围绕客户真实场景，构建六大核心能力',
                'capabilities_lead' => $setting->capabilities_lead ?: '用产品能力解决作业问题，用服务能力保障项目落地，用数字化能力提升长期运营效率。',
                'capabilities' => $setting->capabilities ?: CompanyAboutContent::capabilities(),
                'timeline_eyebrow' => $setting->timeline_eyebrow ?: 'Development Path',
                'timeline_lead' => $setting->timeline_lead ?: '众鼠科技坚持以场景需求驱动产品，以客户价值检验能力，在长期项目落地中不断完善自身体系。',
                'culture_eyebrow' => $setting->culture_eyebrow ?: '· Corporate Culture · 企业文化',
                'honors_eyebrow' => $setting->honors_eyebrow ?: 'Honors & Certifications',
                'team_eyebrow' => $setting->team_eyebrow ?: 'Our Team',
            ])->save();
        }
    }

    public function down(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'intro_eyebrow', 'intro_visual_title', 'intro_visual_text', 'intro_side_image',
                'capabilities_eyebrow', 'capabilities_title', 'capabilities_lead', 'capabilities',
                'timeline_eyebrow', 'timeline_lead', 'culture_eyebrow', 'honors_eyebrow', 'team_eyebrow',
            ]);
        });
    }
};

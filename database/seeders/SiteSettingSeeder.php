<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'label' => '网站名称', 'value' => '我的官网', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_description', 'label' => '网站简介', 'value' => '欢迎访问我们的官方网站', 'group' => 'general', 'type' => 'textarea'],
            ['key' => 'site_logo', 'label' => '网站 Logo', 'value' => null, 'group' => 'general', 'type' => 'image'],
            ['key' => 'contact_email', 'label' => '联系邮箱', 'value' => 'contact@example.com', 'group' => 'contact', 'type' => 'email'],
            ['key' => 'contact_phone', 'label' => '联系电话', 'value' => '400-000-0000', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'contact_address', 'label' => '公司地址', 'value' => '请填写公司地址', 'group' => 'contact', 'type' => 'textarea'],
            ['key' => 'seo_keywords', 'label' => 'SEO 关键词', 'value' => '官网,企业', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'icp_number', 'label' => 'ICP 备案号', 'value' => '', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_copyright', 'label' => '版权信息', 'value' => '© 2026 我的官网. All rights reserved.', 'group' => 'footer', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $setting['key']],
                $setting,
            );
        }
    }
}

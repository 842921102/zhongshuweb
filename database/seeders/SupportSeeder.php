<?php

namespace Database\Seeders;

use App\Models\SupportDocument;
use App\Models\SupportPageSetting;
use App\Models\SupportVideo;
use App\Models\SiteNavMenu;
use Illuminate\Database\Seeder;

class SupportSeeder extends Seeder
{
    public function run(): void
    {
        $locale = 'zh-cn';

        SupportPageSetting::query()->updateOrCreate(
            ['locale' => $locale],
            SupportPageSetting::defaultAttributes($locale)
        );

        SiteNavMenu::query()
            ->where('menu_key', 'support')
            ->update(['url' => '/support']);

        $docs = [
            ['璇光一号', '产品手册', 'v1.0', '2026-04', 2, '26KB'],
            ['洗地机画册', '产品手册', 'v1.0', '2026-04', 5, '26KB'],
            ['割草机', '产品手册', 'v1.0', '2026-04', 2, '26KB'],
        ];

        foreach ($docs as $i => [$title, $cat, $ver, $month, $pages, $size]) {
            SupportDocument::query()->updateOrCreate(
                ['locale' => $locale, 'title' => $title],
                [
                    'category' => $cat,
                    'version' => $ver,
                    'published_label' => $month,
                    'page_count' => $pages,
                    'file_path' => '',
                    'file_size_label' => $size,
                    'sort_order' => $i,
                    'is_active' => false,
                ]
            );
        }

        $videos = [
            ['开放式扫地机', '02:00', '宣传视频', 8],
            ['纯吸式扫地机', '02:30', '宣传视频', 0],
            ['上海众鼠智能设备有限公司', '02:00', '宣传视频', 1],
        ];

        foreach ($videos as $i => [$title, $duration, $tag, $plays]) {
            SupportVideo::query()->updateOrCreate(
                ['locale' => $locale, 'title' => $title],
                [
                    'cover_image' => null,
                    'video_url' => '',
                    'duration_label' => $duration,
                    'tag' => $tag,
                    'play_count' => $plays,
                    'sort_order' => $i,
                    'is_active' => false,
                ]
            );
        }
    }
}

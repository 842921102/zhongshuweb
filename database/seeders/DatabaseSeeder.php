<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SiteSettingSeeder::class,
            HomeContentSeeder::class,
            CompanyAboutSeeder::class,
            SupportSeeder::class,
            JoinUsSeeder::class,
        ]);

        \App\Models\ProductPageSetting::forLocale('zh-cn');
    }
}

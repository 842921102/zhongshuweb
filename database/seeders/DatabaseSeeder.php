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
            IndustrySolutionSeeder::class,
            RolePermissionSeeder::class,
        ]);

        \App\Models\ProductPageSetting::forLocale('zh-cn');
        \App\Models\IndustrySolutionPageSetting::forLocale('zh-cn');
    }
}

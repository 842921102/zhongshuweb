<?php

namespace App\Providers;

use App\Services\CosStorageService;
use Illuminate\Support\ServiceProvider;

class CosStorageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('business_cos_settings')) {
                app(CosStorageService::class)->applyDiskConfig();
            }
        } catch (\Throwable) {
            // 迁移未完成或数据库不可用时忽略
        }
    }
}

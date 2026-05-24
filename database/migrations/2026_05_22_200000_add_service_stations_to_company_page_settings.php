<?php

use App\Models\CompanyPageSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->string('global_station_eyebrow', 120)->nullable()->after('global_map_image');
            $table->string('global_station_heading', 200)->nullable()->after('global_station_eyebrow');
            $table->json('service_stations')->nullable()->after('office_groups');
        });

        foreach (CompanyPageSetting::query()->cursor() as $setting) {
            $groups = \App\Casts\JsonArrayCast::normalize($setting->getRawOriginal('office_groups'));
            $isLegacy = ! empty($groups) && isset(($groups[0] ?? [])['count']);

            $setting->global_station_eyebrow = $setting->global_station_eyebrow
                ?: '· SERVICE STATION · 服务站';
            $setting->global_station_heading = $setting->global_station_heading
                ?: '服务站 覆盖核心区域';
            $setting->forceFill([
                'service_stations' => $isLegacy || empty($groups)
                    ? CompanyPageSetting::defaultServiceStations()
                    : $groups,
            ])->save();
        }

        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->dropColumn('office_groups');
        });
    }

    public function down(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->json('office_groups')->nullable()->after('global_metrics');
        });

        foreach (CompanyPageSetting::query()->cursor() as $setting) {
            $setting->office_groups = [
                ['count' => '1', 'title' => '总部', 'places' => '深圳'],
                ['count' => '2', 'title' => '研发中心', 'places' => '深圳 · 成都'],
                ['count' => '3', 'title' => '制造基地', 'places' => '华南 · 华东'],
            ];
            $setting->save();
        }

        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->dropColumn(['global_station_eyebrow', 'global_station_heading', 'service_stations']);
        });
    }
};

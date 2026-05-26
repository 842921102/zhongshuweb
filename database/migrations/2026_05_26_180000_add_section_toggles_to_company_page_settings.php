<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->boolean('banner_enabled')->default(true)->after('hero_poster_mobile');
            $table->boolean('intro_enabled')->default(true)->after('intro_side_image_mobile');
            $table->boolean('capabilities_enabled')->default(true)->after('capabilities');
            $table->boolean('service_stations_enabled')->default(true)->after('service_stations');
            $table->boolean('timeline_enabled')->default(true)->after('timeline_lead');
            $table->boolean('culture_enabled')->default(true)->after('culture_mission_text');
            $table->boolean('honors_enabled')->default(true)->after('honors_subtitle');
            $table->boolean('team_enabled')->default(true)->after('team_tech_subtitle');
        });
    }

    public function down(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'banner_enabled',
                'intro_enabled',
                'capabilities_enabled',
                'service_stations_enabled',
                'timeline_enabled',
                'culture_enabled',
                'honors_enabled',
                'team_enabled',
            ]);
        });
    }
};

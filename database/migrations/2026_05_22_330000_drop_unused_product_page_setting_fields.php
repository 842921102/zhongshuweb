<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_page_settings', function (Blueprint $table) {
            $table->dropColumn(['nav_kicker', 'catalog_kicker', 'banner_height']);
        });
    }

    public function down(): void
    {
        Schema::table('product_page_settings', function (Blueprint $table) {
            $table->string('nav_kicker', 120)->nullable()->after('banner_video_poster');
            $table->string('catalog_kicker', 120)->nullable()->after('nav_kicker');
            $table->unsignedSmallInteger('banner_height')->default(0)->after('catalog_kicker');
        });
    }
};

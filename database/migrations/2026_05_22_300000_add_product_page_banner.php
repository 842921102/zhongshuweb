<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_page_settings', function (Blueprint $table) {
            $table->string('banner_media_type', 20)->default('image')->after('meta_keywords');
            $table->string('banner_image_pc', 500)->nullable()->after('banner_media_type');
            $table->string('banner_image_mobile', 500)->nullable()->after('banner_image_pc');
            $table->string('banner_video_url', 500)->nullable()->after('banner_image_mobile');
            $table->string('banner_video_poster', 500)->nullable()->after('banner_video_url');
            $table->unsignedSmallInteger('banner_height')->default(400)->after('banner_video_poster');
        });
    }

    public function down(): void
    {
        Schema::table('product_page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'banner_media_type',
                'banner_image_pc',
                'banner_image_mobile',
                'banner_video_url',
                'banner_video_poster',
                'banner_height',
            ]);
        });
    }
};

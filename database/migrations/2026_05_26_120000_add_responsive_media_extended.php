<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_studies', function (Blueprint $table): void {
            $table->string('cover_image_mobile')->nullable()->after('cover_image');
        });

        Schema::table('join_page_settings', function (Blueprint $table): void {
            $table->string('hero_image_mobile')->nullable()->after('hero_image');
            $table->string('culture_image_mobile')->nullable()->after('culture_image');
        });

        Schema::table('company_page_settings', function (Blueprint $table): void {
            $table->string('hero_media_mobile')->nullable()->after('hero_media_url');
            $table->string('hero_poster_mobile')->nullable()->after('hero_poster_url');
            $table->string('intro_side_image_mobile')->nullable()->after('intro_side_image');
        });

        Schema::table('support_videos', function (Blueprint $table): void {
            $table->string('cover_image_mobile')->nullable()->after('cover_image');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->string('detail_hero_image_mobile')->nullable()->after('detail_hero_image');
            $table->string('contact_bg_image_mobile')->nullable()->after('contact_bg_image');
        });
    }

    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table): void {
            $table->dropColumn('cover_image_mobile');
        });

        Schema::table('join_page_settings', function (Blueprint $table): void {
            $table->dropColumn(['hero_image_mobile', 'culture_image_mobile']);
        });

        Schema::table('company_page_settings', function (Blueprint $table): void {
            $table->dropColumn(['hero_media_mobile', 'hero_poster_mobile', 'intro_side_image_mobile']);
        });

        Schema::table('support_videos', function (Blueprint $table): void {
            $table->dropColumn('cover_image_mobile');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['detail_hero_image_mobile', 'contact_bg_image_mobile']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->default('zh-cn')->unique();
            $table->string('meta_title', 160)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('hero_media_url', 500)->nullable();
            $table->string('hero_media_type', 20)->default('image');
            $table->string('hero_poster_url', 500)->nullable();
            $table->string('intro_title', 120)->default('关于众鼠');
            $table->text('intro_body')->nullable();
            $table->string('global_title', 120)->default('全国布局');
            $table->string('global_map_image', 500)->nullable();
            $table->json('global_metrics')->nullable();
            $table->json('office_groups')->nullable();
            $table->string('timeline_title', 120)->default('发展历程');
            $table->string('culture_title', 120)->default('企业文化');
            $table->string('culture_mission_label', 80)->default('使命');
            $table->text('culture_mission_text')->nullable();
            $table->string('honors_title', 120)->default('品牌荣誉');
            $table->timestamps();
        });

        Schema::create('company_milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->string('month_label', 20)->nullable();
            $table->string('title', 500);
            $table->string('image', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();

            $table->index(['locale', 'year', 'sort_order']);
        });

        Schema::create('company_culture_values', function (Blueprint $table) {
            $table->id();
            $table->string('label', 80);
            $table->string('icon', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });

        Schema::create('company_honors', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('image', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_honors');
        Schema::dropIfExists('company_culture_values');
        Schema::dropIfExists('company_milestones');
        Schema::dropIfExists('company_page_settings');
    }
};

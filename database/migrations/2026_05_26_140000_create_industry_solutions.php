<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industry_solution_page_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('locale', 10)->default('zh-cn')->unique();
            $table->string('page_title', 120)->default('行业案例');
            $table->text('page_subtitle')->nullable();
            $table->string('banner_video_url', 500)->nullable();
            $table->string('banner_image_pc', 500)->nullable();
            $table->string('banner_image_mobile', 500)->nullable();
            $table->unsignedSmallInteger('banner_height')->default(640);
            $table->string('detail_button_text', 40)->default('查看方案');
            $table->string('meta_title', 160)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('industry_solutions', function (Blueprint $table): void {
            $table->id();
            $table->string('locale', 10)->default('zh-cn')->index();
            $table->string('title');
            $table->string('slug', 120);
            $table->text('summary')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->string('cover_image_mobile', 500)->nullable();
            $table->longText('content')->nullable();
            $table->string('detail_button_text', 40)->nullable();
            $table->string('external_url', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title', 160)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->unique(['locale', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('industry_solutions');
        Schema::dropIfExists('industry_solution_page_settings');
    }
};

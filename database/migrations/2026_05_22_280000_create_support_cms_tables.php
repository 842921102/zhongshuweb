<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->default('zh-cn')->unique();
            $table->string('meta_title', 160)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('hero_image_pc', 500)->nullable();
            $table->string('hero_image_mobile', 500)->nullable();
            $table->unsignedSmallInteger('hero_height')->default(450);
            $table->string('docs_kicker', 120)->nullable();
            $table->string('docs_title', 200)->nullable();
            $table->string('videos_kicker', 120)->nullable();
            $table->string('videos_title', 200)->nullable();
            $table->string('service_kicker', 120)->nullable();
            $table->string('service_form_title', 200)->nullable();
            $table->string('contact_title', 200)->nullable();
            $table->string('contact_phone_label', 80)->nullable();
            $table->string('contact_phone', 40)->nullable();
            $table->string('contact_email_label', 80)->nullable();
            $table->string('contact_email', 120)->nullable();
            $table->string('contact_address_label', 80)->nullable();
            $table->string('contact_address', 500)->nullable();
            $table->json('doc_categories')->nullable();
            $table->json('form_topics')->nullable();
            $table->timestamps();
        });

        Schema::create('support_documents', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->default('zh-cn');
            $table->string('title');
            $table->string('category', 80);
            $table->string('version', 40)->nullable();
            $table->string('published_label', 40)->nullable();
            $table->unsignedSmallInteger('page_count')->nullable();
            $table->string('file_path', 500);
            $table->string('file_size_label', 40)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('support_videos', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->default('zh-cn');
            $table->string('title');
            $table->string('cover_image', 500)->nullable();
            $table->string('video_url', 500);
            $table->string('duration_label', 20)->nullable();
            $table->string('tag', 80)->nullable();
            $table->unsignedInteger('play_count')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('support_service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('phone', 20);
            $table->string('email', 120)->nullable();
            $table->string('region', 200);
            $table->string('province_code', 20)->nullable();
            $table->string('city_code', 20)->nullable();
            $table->string('district_code', 20)->nullable();
            $table->string('topic', 80);
            $table->string('status', 20)->default('pending');
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_service_requests');
        Schema::dropIfExists('support_videos');
        Schema::dropIfExists('support_documents');
        Schema::dropIfExists('support_page_settings');
    }
};

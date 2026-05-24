<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('join_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->default('zh-cn')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('culture_image')->nullable();
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_title_highlight')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_cta_primary')->nullable();
            $table->string('hero_cta_secondary')->nullable();
            $table->string('why_kicker')->nullable();
            $table->string('why_title')->nullable();
            $table->text('why_subtitle')->nullable();
            $table->string('culture_kicker')->nullable();
            $table->string('culture_title')->nullable();
            $table->text('culture_subtitle')->nullable();
            $table->string('jobs_kicker')->nullable();
            $table->string('jobs_title')->nullable();
            $table->text('jobs_subtitle')->nullable();
            $table->string('all_jobs_label')->default('全部岗位');
            $table->string('process_kicker')->nullable();
            $table->string('process_title')->nullable();
            $table->text('process_subtitle')->nullable();
            $table->string('welfare_kicker')->nullable();
            $table->string('welfare_title')->nullable();
            $table->text('welfare_subtitle')->nullable();
            $table->string('contact_kicker')->nullable();
            $table->string('contact_title')->nullable();
            $table->text('contact_subtitle')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_locations')->nullable();
            $table->string('contact_email_subject_tip')->nullable();
            $table->string('apply_label')->default('立即投递');
            $table->string('send_resume_label')->default('发送简历');
            $table->timestamps();
        });

        Schema::create('join_job_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
            $table->unique(['slug', 'locale']);
        });

        Schema::create('join_why_cards', function (Blueprint $table) {
            $table->id();
            $table->string('icon_char', 10)->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });

        Schema::create('join_culture_cards', function (Blueprint $table) {
            $table->id();
            $table->string('step_label')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });

        Schema::create('join_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('join_job_categories')->nullOnDelete();
            $table->string('title');
            $table->string('department_label')->nullable();
            $table->string('location')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('experience')->nullable();
            $table->text('summary')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });

        Schema::create('join_process_steps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });

        Schema::create('join_welfare_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('join_positions');
        Schema::dropIfExists('join_welfare_cards');
        Schema::dropIfExists('join_process_steps');
        Schema::dropIfExists('join_culture_cards');
        Schema::dropIfExists('join_why_cards');
        Schema::dropIfExists('join_job_categories');
        Schema::dropIfExists('join_page_settings');
    }
};

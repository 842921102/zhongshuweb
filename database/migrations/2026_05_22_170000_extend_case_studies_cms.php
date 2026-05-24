<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_study_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('slug', 80);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();

            $table->unique(['slug', 'locale']);
        });

        Schema::create('case_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->default('zh-cn')->unique();
            $table->string('page_title', 120)->default('客户案例');
            $table->text('page_subtitle')->nullable();
            $table->string('meta_title', 160)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
        });

        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('slug', 120)->nullable()->after('title');
            $table->foreignId('category_id')
                ->nullable()
                ->after('slug')
                ->constrained('case_study_categories')
                ->nullOnDelete();
            $table->text('excerpt')->nullable()->after('summary');
            $table->boolean('is_featured')->default(false)->after('is_home_show');
            $table->json('product_tags')->nullable()->after('content');
            $table->timestamp('published_at')->nullable()->after('product_tags');
            $table->string('meta_title', 160)->nullable()->after('published_at');
            $table->string('meta_description', 500)->nullable()->after('meta_title');

            $table->unique(['slug', 'locale']);
            $table->index(['locale', 'is_active', 'is_featured', 'sort_order']);
            $table->index(['category_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropUnique(['slug', 'locale']);
            $table->dropIndex(['locale', 'is_active', 'is_featured', 'sort_order']);
            $table->dropIndex(['category_id', 'locale']);
            $table->dropColumn([
                'slug', 'category_id', 'excerpt', 'is_featured',
                'product_tags', 'published_at', 'meta_title', 'meta_description',
            ]);
        });

        Schema::dropIfExists('case_page_settings');
        Schema::dropIfExists('case_study_categories');
    }
};

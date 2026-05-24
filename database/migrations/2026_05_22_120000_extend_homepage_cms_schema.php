<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('image_mobile')->nullable()->after('image');
            $table->string('locale', 10)->default('zh-cn')->after('position');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            $table->string('subtitle')->nullable()->after('name');
            $table->text('description')->nullable()->after('subtitle');
            $table->string('icon')->nullable()->after('description');
            $table->string('cover_image')->nullable()->after('icon');
            $table->string('link')->nullable()->after('cover_image');
            $table->boolean('is_home_show')->default(false)->after('is_active');
            $table->boolean('is_home_featured')->default(false)->after('is_home_show');
            $table->boolean('is_station_tab')->default(false)->after('is_home_featured');
            $table->string('locale', 10)->default('zh-cn')->after('is_station_tab');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('model_no')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('summary')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('home_image')->nullable();
            $table->string('detail_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_home_show')->default(false);
            $table->boolean('is_home_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });

        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('region')->nullable();
            $table->string('scene_type')->nullable();
            $table->string('summary')->nullable();
            $table->string('cover_image')->nullable();
            $table->longText('content')->nullable();
            $table->string('detail_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_home_show')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });

        Schema::create('site_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('link')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_home_show')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });

        Schema::create('site_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('value');
            $table->string('unit')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_home_show')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_home_show')->default(false)->after('is_featured');
            $table->unsignedInteger('sort_order')->default(0)->after('is_home_show');
            $table->string('locale', 10)->default('zh-cn')->after('sort_order');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('page_key')->nullable()->after('slug');
            $table->string('button_text')->nullable()->after('cover_image');
            $table->string('button_url')->nullable()->after('button_text');
            $table->text('excerpt')->nullable()->after('subtitle');
            $table->string('locale', 10)->default('zh-cn')->after('is_published');
        });

        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();
            $table->string('section_name');
            $table->string('title')->nullable();
            $table->string('title_highlight')->nullable();
            $table->string('subtitle')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('background_color')->nullable();
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });

        Schema::create('site_footer_links', function (Blueprint $table) {
            $table->id();
            $table->string('group_key');
            $table->string('group_label');
            $table->string('label');
            $table->string('url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_footer_links');
        Schema::dropIfExists('home_sections');
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['page_key', 'button_text', 'button_url', 'excerpt', 'locale']);
        });
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['is_home_show', 'sort_order', 'locale']);
        });
        Schema::dropIfExists('site_statistics');
        Schema::dropIfExists('site_partners');
        Schema::dropIfExists('case_studies');
        Schema::dropIfExists('products');
        Schema::table('categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropColumn([
                'subtitle', 'description', 'icon', 'cover_image', 'link',
                'is_home_show', 'is_home_featured', 'is_station_tab', 'locale',
            ]);
        });
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['image_mobile', 'locale']);
        });
    }
};

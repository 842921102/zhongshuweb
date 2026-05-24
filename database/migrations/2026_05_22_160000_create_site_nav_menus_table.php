<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_nav_menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_key', 50)->nullable()->comment('系统标识，如 home、product_mega');
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('site_nav_menus')
                ->nullOnDelete();
            $table->string('menu_type', 20)->default('link')->comment('link=普通链接, product_mega=产品下拉');
            $table->string('label', 120);
            $table->string('url', 500)->default('#');
            $table->string('route_keys', 100)->nullable()->comment('data-route，逗号分隔');
            $table->string('search_keywords', 255)->nullable()->comment('站内搜索 data-search');
            $table->boolean('open_in_new_tab')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();

            $table->unique(['menu_key', 'locale']);
            $table->index(['locale', 'parent_id', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_nav_menus');
    }
};

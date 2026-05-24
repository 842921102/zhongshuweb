<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug', 120)->nullable()->after('name');
            $table->json('metrics')->nullable()->after('summary');
        });

        Schema::create('product_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->default('zh-cn')->unique();
            $table->string('meta_title', 160)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('nav_kicker', 120)->nullable();
            $table->string('catalog_kicker', 120)->nullable();
            $table->string('view_all_label', 40)->nullable();
            $table->string('all_label', 20)->nullable();
            $table->string('detail_label', 40)->nullable();
            $table->string('catalog_empty', 120)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_page_settings');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['slug', 'metrics']);
        });
    }
};

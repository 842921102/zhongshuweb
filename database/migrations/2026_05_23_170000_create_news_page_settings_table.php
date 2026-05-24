<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->default('zh-cn')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('banner_image_pc')->nullable();
            $table->string('banner_image_mobile')->nullable();
            $table->unsignedSmallInteger('banner_height')->default(450);
            $table->string('read_more_label')->default('阅读全文');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_page_settings');
    }
};

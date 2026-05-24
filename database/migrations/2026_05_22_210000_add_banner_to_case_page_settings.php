<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_page_settings', function (Blueprint $table) {
            $table->string('banner_image_pc')->nullable()->after('page_subtitle');
            $table->string('banner_image_mobile')->nullable()->after('banner_image_pc');
            $table->unsignedSmallInteger('banner_height')->default(420)->after('banner_image_mobile');
        });
    }

    public function down(): void
    {
        Schema::table('case_page_settings', function (Blueprint $table) {
            $table->dropColumn(['banner_image_pc', 'banner_image_mobile', 'banner_height']);
        });
    }
};

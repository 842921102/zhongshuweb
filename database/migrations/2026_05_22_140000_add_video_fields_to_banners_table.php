<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('media_type', 20)->default('image')->after('subtitle');
            $table->string('video')->nullable()->after('image_mobile');
            $table->string('video_mobile')->nullable()->after('video');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['media_type', 'video', 'video_mobile']);
        });
    }
};

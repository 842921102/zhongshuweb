<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->string('overlay_title_color', 20)->nullable()->after('link');
            $table->string('overlay_subtitle_color', 20)->nullable()->after('overlay_title_color');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->string('overlay_title_color', 20)->nullable()->after('subtitle');
            $table->string('overlay_subtitle_color', 20)->nullable()->after('overlay_title_color');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn(['overlay_title_color', 'overlay_subtitle_color']);
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['overlay_title_color', 'overlay_subtitle_color']);
        });
    }
};

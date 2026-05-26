<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->string('cover_image_mobile')->nullable()->after('cover_image');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->string('cover_image_mobile')->nullable()->after('cover_image');
            $table->string('home_image_mobile')->nullable()->after('home_image');
        });

        Schema::table('articles', function (Blueprint $table): void {
            $table->string('cover_image_mobile')->nullable()->after('cover_image');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn('cover_image_mobile');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['cover_image_mobile', 'home_image_mobile']);
        });

        Schema::table('articles', function (Blueprint $table): void {
            $table->dropColumn('cover_image_mobile');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();

            $table->unique(['slug', 'locale']);
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('id')
                ->constrained('article_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();
        });

        Schema::dropIfExists('article_categories');
    }
};

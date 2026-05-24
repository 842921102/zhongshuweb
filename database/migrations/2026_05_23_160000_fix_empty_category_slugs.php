<?php

use App\Models\Category;
use App\Support\UniqueSlug;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Category::query()
            ->where(function ($q): void {
                $q->whereNull('slug')->orWhere('slug', '');
            })
            ->orderBy('id')
            ->each(function (Category $category): void {
                if (blank($category->name)) {
                    $category->slug = 'category-'.$category->id;
                } else {
                    $category->slug = UniqueSlug::for($category, $category->name, 'category');
                }
                $category->saveQuietly();
            });
    }

    public function down(): void
    {
        // 数据修复，不回滚
    }
};

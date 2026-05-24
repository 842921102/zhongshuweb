<?php

use App\Models\ArticleCategory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        ArticleCategory::ensureDefaults('zh-cn');
    }

    public function down(): void
    {
        // 保留数据
    }
};

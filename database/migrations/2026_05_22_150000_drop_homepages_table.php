<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('homepages');
    }

    public function down(): void
    {
        // 旧版首页配置已移除，不再恢复 homepages 表
    }
};

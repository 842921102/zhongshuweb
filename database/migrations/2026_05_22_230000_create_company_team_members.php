<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->string('team_title', 120)->default('团队介绍')->after('honors_title');
            $table->string('team_tech_subtitle', 200)->default('我们技术团队人员介绍')->after('team_title');
        });

        Schema::create('company_team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('role', 120)->nullable();
            $table->text('bio')->nullable();
            $table->string('photo', 500)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();

            $table->index(['locale', 'is_featured', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_team_members');

        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->dropColumn(['team_title', 'team_tech_subtitle']);
        });
    }
};

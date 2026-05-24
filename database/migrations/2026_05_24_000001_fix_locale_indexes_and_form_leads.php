<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->dropUnique('home_sections_section_key_unique');
            $table->unique(['section_key', 'locale']);
        });

        Schema::table('product_consultations', function (Blueprint $table) {
            $table->string('locale', 10)->default('zh-cn')->after('status');
            $table->index(['locale', 'created_at']);
        });

        Schema::table('support_service_requests', function (Blueprint $table) {
            $table->string('locale', 10)->default('zh-cn')->after('status');
            $table->index(['locale', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('support_service_requests', function (Blueprint $table) {
            $table->dropIndex('support_service_requests_locale_created_at_index');
            $table->dropColumn('locale');
        });

        Schema::table('product_consultations', function (Blueprint $table) {
            $table->dropIndex('product_consultations_locale_created_at_index');
            $table->dropColumn('locale');
        });

        Schema::table('home_sections', function (Blueprint $table) {
            $table->dropUnique('home_sections_section_key_locale_unique');
            $table->unique('section_key');
        });
    }
};

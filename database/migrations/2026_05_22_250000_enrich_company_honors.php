<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->string('honors_subtitle', 200)->nullable()->after('honors_title');
        });

        Schema::table('company_honors', function (Blueprint $table) {
            $table->string('category', 40)->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('company_honors', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->dropColumn('honors_subtitle');
        });
    }
};

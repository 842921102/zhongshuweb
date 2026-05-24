<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_page_settings', function (Blueprint $table) {
            $table->string('all_category_label', 40)->default('全部')->after('read_more_label');
        });
    }

    public function down(): void
    {
        Schema::table('news_page_settings', function (Blueprint $table) {
            $table->dropColumn('all_category_label');
        });
    }
};

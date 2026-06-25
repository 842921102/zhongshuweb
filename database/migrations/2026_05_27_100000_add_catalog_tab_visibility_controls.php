<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('show_in_catalog')->default(false)->after('is_station_tab');
        });

        Schema::table('product_page_settings', function (Blueprint $table) {
            $table->boolean('catalog_tabs_enabled')->default(true)->after('catalog_empty');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('show_in_catalog');
        });

        Schema::table('product_page_settings', function (Blueprint $table) {
            $table->dropColumn('catalog_tabs_enabled');
        });
    }
};

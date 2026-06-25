<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_footer_links')
            ->where('label', '联系我们')
            ->where('url', '/about#contact')
            ->update(['url' => '/join-us#contact']);

        DB::table('categories')
            ->whereNotNull('parent_id')
            ->where('is_station_tab', true)
            ->where('show_in_catalog', false)
            ->update(['show_in_catalog' => true]);
    }

    public function down(): void
    {
        DB::table('site_footer_links')
            ->where('label', '联系我们')
            ->where('url', '/join-us#contact')
            ->update(['url' => '/about#contact']);
    }
};

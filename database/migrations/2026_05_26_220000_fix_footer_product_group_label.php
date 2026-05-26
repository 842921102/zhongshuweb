<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_footer_links')
            ->where('group_key', 'products')
            ->where('group_label', '产品中')
            ->update(['group_label' => '产品中心']);
    }

    public function down(): void
    {
        DB::table('site_footer_links')
            ->where('group_key', 'products')
            ->where('group_label', '产品中心')
            ->update(['group_label' => '产品中']);
    }
};

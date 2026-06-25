<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_settings')
            ->whereIn('key', [
                'header_logo_default',
                'header_logo_scrolled',
                'footer_logo',
            ])
            ->update(['type' => 'image']);
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->whereIn('key', [
                'header_logo_default',
                'header_logo_scrolled',
                'footer_logo',
            ])
            ->update(['type' => 'text']);
    }
};

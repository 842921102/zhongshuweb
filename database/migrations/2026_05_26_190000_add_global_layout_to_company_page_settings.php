<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->boolean('global_layout_enabled')->default(true)->after('intro_enabled');
            $table->string('global_layout_title', 120)->nullable()->after('global_layout_enabled');
            $table->json('global_layout_stats')->nullable()->after('global_layout_title');
            $table->json('global_layout_markers')->nullable()->after('global_layout_stats');
            $table->json('global_layout_facilities')->nullable()->after('global_layout_markers');
            $table->string('global_layout_map_image', 500)->nullable()->after('global_layout_facilities');
        });
    }

    public function down(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'global_layout_enabled',
                'global_layout_title',
                'global_layout_stats',
                'global_layout_markers',
                'global_layout_facilities',
                'global_layout_map_image',
            ]);
        });
    }
};

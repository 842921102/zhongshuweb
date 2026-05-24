<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'global_title',
                'global_map_image',
                'culture_mission_label',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('company_page_settings', function (Blueprint $table) {
            $table->string('global_title', 120)->default('全国布局')->after('intro_side_image');
            $table->string('global_map_image', 500)->nullable()->after('global_title');
            $table->string('culture_mission_label', 80)->default('使命')->after('culture_title');
        });
    }
};

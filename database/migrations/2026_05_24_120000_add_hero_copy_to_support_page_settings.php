<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_page_settings', function (Blueprint $table) {
            $table->string('hero_eyebrow', 120)->nullable()->after('hero_height');
            $table->string('hero_title', 200)->nullable()->after('hero_eyebrow');
            $table->text('hero_subtitle')->nullable()->after('hero_title');
        });
    }

    public function down(): void
    {
        Schema::table('support_page_settings', function (Blueprint $table) {
            $table->dropColumn(['hero_eyebrow', 'hero_title', 'hero_subtitle']);
        });
    }
};

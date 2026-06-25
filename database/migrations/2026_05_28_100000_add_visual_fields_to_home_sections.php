<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->string('visual_image', 500)->nullable()->after('subtitle');
            $table->string('visual_image_mobile', 500)->nullable()->after('visual_image');
            $table->text('visual_text')->nullable()->after('visual_image_mobile');
            $table->string('visual_button_label', 80)->nullable()->after('visual_text');
            $table->string('visual_button_url', 500)->nullable()->after('visual_button_label');
        });
    }

    public function down(): void
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->dropColumn([
                'visual_image',
                'visual_image_mobile',
                'visual_text',
                'visual_button_label',
                'visual_button_url',
            ]);
        });
    }
};

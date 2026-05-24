<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('hero_video', 500)->nullable()->after('home_image');
            $table->string('hero_poster', 500)->nullable()->after('hero_video');
            $table->json('showcase_images')->nullable()->after('hero_poster');
            $table->string('detail_hero_image', 500)->nullable()->after('showcase_images');
            $table->json('detail_gallery')->nullable()->after('detail_hero_image');
            $table->json('detail_features')->nullable()->after('detail_gallery');
            $table->json('spec_groups')->nullable()->after('detail_features');
            $table->string('spec_document', 500)->nullable()->after('spec_groups');
            $table->json('rights_content')->nullable()->after('spec_document');
            $table->string('contact_bg_image', 500)->nullable()->after('rights_content');
        });

        Schema::create('product_consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name', 120)->nullable();
            $table->string('name', 80);
            $table->string('phone', 20);
            $table->string('email', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('topic', 80)->nullable();
            $table->text('remark')->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_consultations');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'hero_video',
                'hero_poster',
                'showcase_images',
                'detail_hero_image',
                'detail_gallery',
                'detail_features',
                'spec_groups',
                'spec_document',
                'rights_content',
                'contact_bg_image',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('meta_title', 160)->nullable()->after('summary');
            $table->string('meta_description', 500)->nullable()->after('meta_title');
        });

        Schema::table('product_page_settings', function (Blueprint $table) {
            $table->json('detail_labels')->nullable()->after('catalog_empty');
        });
    }

    public function down(): void
    {
        Schema::table('product_page_settings', function (Blueprint $table) {
            $table->dropColumn('detail_labels');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};

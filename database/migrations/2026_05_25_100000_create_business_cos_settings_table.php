<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_cos_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(false);
            $table->boolean('use_for_uploads')->default(false);
            $table->text('secret_id')->nullable();
            $table->text('secret_key')->nullable();
            $table->string('region', 32)->default('ap-guangzhou');
            $table->string('bucket', 128)->nullable();
            $table->string('app_id', 32)->nullable();
            $table->string('path_prefix', 120)->default('uploads');
            $table->string('cdn_domain', 255)->nullable();
            $table->timestamp('last_tested_at')->nullable();
            $table->string('last_test_status', 20)->nullable();
            $table->text('last_test_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_cos_settings');
    }
};

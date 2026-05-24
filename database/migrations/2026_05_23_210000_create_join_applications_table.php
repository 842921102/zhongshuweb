<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('join_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->nullable()->constrained('join_positions')->nullOnDelete();
            $table->string('position_title', 200)->nullable();
            $table->string('name', 80);
            $table->string('phone', 20);
            $table->string('email', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('resume_path', 500)->nullable();
            $table->string('resume_original_name', 255)->nullable();
            $table->text('message')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('admin_note')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('locale', 10)->default('zh-cn');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::table('join_page_settings', function (Blueprint $table) {
            $table->string('form_title')->nullable()->after('send_resume_label');
            $table->string('form_submit_label')->default('提交简历')->after('form_title');
            $table->string('form_success_message')->nullable()->after('form_submit_label');
            $table->string('form_error_message')->nullable()->after('form_success_message');
        });
    }

    public function down(): void
    {
        Schema::table('join_page_settings', function (Blueprint $table) {
            $table->dropColumn(['form_title', 'form_submit_label', 'form_success_message', 'form_error_message']);
        });

        Schema::dropIfExists('join_applications');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_culture_values', function (Blueprint $table) {
            $table->string('subtitle', 120)->nullable()->after('label');
            $table->text('essence')->nullable()->after('subtitle');
            $table->text('practice')->nullable()->after('essence');
        });
    }

    public function down(): void
    {
        Schema::table('company_culture_values', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'essence', 'practice']);
        });
    }
};

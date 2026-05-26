<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Permission::query()
            ->where('name', 'like', 'pages.%')
            ->delete();
    }

    public function down(): void
    {
        //
    }
};

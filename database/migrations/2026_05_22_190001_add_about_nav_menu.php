<?php

use App\Models\SiteNavMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['zh-cn', 'en-us'] as $locale) {
            SiteNavMenu::ensureDefaults($locale);
        }
    }

    public function down(): void
    {
        SiteNavMenu::query()->where('menu_key', 'about')->delete();
    }
};

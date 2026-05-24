<?php

namespace App\Models;

use App\Models\Concerns\HasLocale;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'group_key', 'group_label', 'label', 'url', 'sort_order', 'is_active', 'locale',
])]
class SiteFooterLink extends Model
{
    use HasLocale;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}

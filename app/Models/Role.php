<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'label', 'description', 'is_system', 'sort_order'])]
class Role extends Model
{
    public const SUPER_ADMIN = 'super-admin';

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->orderBy('permissions.sort_order');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->name === self::SUPER_ADMIN;
    }

    public static function generateNameFromLabel(string $label): string
    {
        $slug = Str::slug($label);

        if ($slug !== '') {
            return $slug;
        }

        return 'role-'.Str::lower(Str::random(8));
    }

    protected static function booted(): void
    {
        static::deleting(function (Role $role): bool {
            return ! $role->is_system;
        });
    }
}

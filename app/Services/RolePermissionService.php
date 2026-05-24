<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\AdminPermissionRegistry;
use Illuminate\Support\Facades\DB;

class RolePermissionService
{
    public function syncPermissions(): void
    {
        foreach (AdminPermissionRegistry::definitions() as $definition) {
            Permission::query()->updateOrCreate(
                ['name' => $definition['name']],
                $definition,
            );
        }
    }

    public function ensureSuperAdminRole(): Role
    {
        $role = Role::query()->firstOrCreate(
            ['name' => Role::SUPER_ADMIN],
            [
                'label' => '超级管理员',
                'description' => '拥有全部后台权限，系统内置角色。',
                'is_system' => true,
                'sort_order' => 0,
            ],
        );

        $role->permissions()->sync(Permission::query()->pluck('id'));

        return $role;
    }

    public function assignSuperAdminToUsersWithoutRoles(): void
    {
        $role = Role::query()->where('name', Role::SUPER_ADMIN)->first();

        if (! $role) {
            return;
        }

        User::query()
            ->whereDoesntHave('roles')
            ->each(fn (User $user) => $user->roles()->syncWithoutDetaching([$role->id]));
    }

    public function bootstrap(): void
    {
        DB::transaction(function (): void {
            $this->syncPermissions();
            $this->ensureSuperAdminRole();
            $this->assignSuperAdminToUsersWithoutRoles();
        });
    }
}

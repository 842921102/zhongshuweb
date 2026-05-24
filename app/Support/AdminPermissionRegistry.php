<?php

namespace App\Support;

class AdminPermissionRegistry
{
  /**
   * @return array<string, array{label: string, group: string, model: class-string|null, sort: int}>
   */
    public static function modules(): array
    {
        return config('admin_permissions.modules', []);
    }

    /**
     * @return array<string, string>
     */
    public static function actions(): array
    {
        return config('admin_permissions.actions', []);
    }

    /**
     * @return list<array{name: string, label: string, group: string, module: string, action: string, sort_order: int}>
     */
    public static function definitions(): array
    {
        $definitions = [];
        $actionLabels = self::actions();

        foreach (self::modules() as $module => $meta) {
            $moduleSort = (int) ($meta['sort'] ?? 0);

            foreach ($actionLabels as $action => $actionLabel) {
                $definitions[] = [
                    'name' => "{$module}.{$action}",
                    'label' => ($meta['label'] ?? $module).$actionLabel,
                    'group' => $meta['group'] ?? '其他',
                    'module' => $module,
                    'action' => $action,
                    'sort_order' => ($moduleSort * 10) + self::actionSort($action),
                ];
            }
        }

        return $definitions;
    }

    public static function moduleForModel(string $modelClass): ?string
    {
        foreach (self::modules() as $module => $meta) {
            if (($meta['model'] ?? null) === $modelClass) {
                return $module;
            }
        }

        return null;
    }

    public static function permissionName(string $module, string $action): string
    {
        return "{$module}.{$action}";
    }

    public static function mapPolicyAbility(string $ability): string
    {
        return match ($ability) {
            'viewAny', 'view' => 'view',
            'create' => 'create',
            'update', 'restore', 'reorder', 'replicate', 'restoreAny' => 'update',
            'delete', 'deleteAny', 'forceDelete', 'forceDeleteAny' => 'delete',
            default => $ability,
        };
    }

    public static function permissionExists(string $name): bool
    {
        return in_array($name, array_column(self::definitions(), 'name'), true);
    }

    private static function actionSort(string $action): int
    {
        return match ($action) {
            'view' => 1,
            'create' => 2,
            'update' => 3,
            'delete' => 4,
            default => 9,
        };
    }
}

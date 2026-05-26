<?php

namespace App\Support;

use App\Models\User;

class AdminPermissionRegistry
{
    /**
     * 页面设置类模块：拥有主模块同操作权限时，可访问对应「列表页/页设置」。
     *
     * @return array<string, list<string>>
     */
    public static function viewInheritanceSources(): array
    {
        return [
            'industry_solution_page_settings' => ['industry_solutions'],
            'product_page_settings' => ['products'],
            'case_page_settings' => ['case_studies'],
            'news_page_settings' => ['articles'],
            'support_page_settings' => [
                'support_documents',
                'support_videos',
                'support_service_requests',
            ],
            'join_page_settings' => [
                'join_positions',
                'join_applications',
                'join_job_categories',
                'join_why_cards',
                'join_culture_cards',
                'join_process_steps',
                'join_welfare_cards',
            ],
        ];
    }
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

    /**
     * @return bool|null true=允许, false=拒绝, null=未注册该权限（交默认策略）
     */
    public static function checkUserModulePermission(User $user, string $module, string $action): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        $permission = self::permissionName($module, $action);

        if (! self::permissionExists($permission)) {
            return null;
        }

        if ($user->hasPermission($permission)) {
            return true;
        }

        foreach (self::viewInheritanceSources()[$module] ?? [] as $sourceModule) {
            foreach (self::inheritedSourceActions($module, $action) as $sourceAction) {
                $sourcePermission = self::permissionName($sourceModule, $sourceAction);

                if (self::permissionExists($sourcePermission) && $user->hasPermission($sourcePermission)) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function isPageSettingModule(string $module): bool
    {
        return array_key_exists($module, self::viewInheritanceSources());
    }

    /**
     * 页设置模块通常只有一条记录可编辑：有主模块「查看」即可进入编辑页。
     *
     * @return list<string>
     */
    private static function inheritedSourceActions(string $module, string $action): array
    {
        $actions = [$action];

        if (! self::isPageSettingModule($module)) {
            return $actions;
        }

        if (in_array($action, ['update', 'create', 'replicate', 'restore'], true)) {
            $actions[] = 'view';
        }

        if (in_array($action, ['delete', 'forceDelete'], true)) {
            $actions[] = 'view';
            $actions[] = 'delete';
        }

        return array_values(array_unique($actions));
    }

    public static function userCanViewModule(?User $user, string $module): bool
    {
        if (! $user) {
            return false;
        }

        return self::checkUserModulePermission($user, $module, 'view') === true;
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

<?php

namespace App\Services;

use App\Filament\Resources\JoinApplications\JoinApplicationResource;
use App\Filament\Resources\ProductConsultations\ProductConsultationResource;
use App\Filament\Resources\SupportServiceRequests\SupportServiceRequestResource;
use App\Models\JoinApplication;
use App\Models\ProductConsultation;
use App\Models\SupportServiceRequest;
use App\Models\User;
use App\Support\AdminPermissionRegistry;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AdminSubmissionStats
{
    public const MODULE_JOIN = 'join_applications';

    public const MODULE_PRODUCT = 'product_consultations';

    public const MODULE_SUPPORT = 'support_service_requests';

    /**
     * @return list<array{
     *     key: string,
     *     label: string,
     *     description: string,
     *     total: int,
     *     pending: int,
     *     today: int,
     *     url: string|null,
     *     color: string,
     * }>
     */
    public static function channels(?User $user = null): array
    {
        $user ??= auth()->user();
        $channels = [];

        if (static::userCanView($user, self::MODULE_JOIN)) {
            $channels[] = static::channel(
                key: 'join',
                label: '简历投递',
                description: '加入我们 · 表单 + 简历附件',
                total: JoinApplication::query()->count(),
                pending: JoinApplication::query()->where('status', JoinApplication::STATUS_PENDING)->count(),
                today: JoinApplication::query()->whereDate('created_at', Carbon::today())->count(),
                url: JoinApplicationResource::getUrl(),
                color: 'warning',
            );
        }

        if (static::userCanView($user, self::MODULE_PRODUCT)) {
            $channels[] = static::channel(
                key: 'product',
                label: '产品咨询',
                description: '产品详情 · 预约咨询表单',
                total: ProductConsultation::query()->count(),
                pending: ProductConsultation::query()->where('status', 'pending')->count(),
                today: ProductConsultation::query()->whereDate('created_at', Carbon::today())->count(),
                url: ProductConsultationResource::getUrl(),
                color: 'info',
            );
        }

        if (static::userCanView($user, self::MODULE_SUPPORT)) {
            $channels[] = static::channel(
                key: 'support',
                label: '售后申请',
                description: '技术支持 · 售后服务表单',
                total: SupportServiceRequest::query()->count(),
                pending: SupportServiceRequest::query()->where('status', 'pending')->count(),
                today: SupportServiceRequest::query()->whereDate('created_at', Carbon::today())->count(),
                url: SupportServiceRequestResource::getUrl(),
                color: 'success',
            );
        }

        return $channels;
    }

    public static function totalPending(?User $user = null): int
    {
        return array_sum(array_column(static::channels($user), 'pending'));
    }

    public static function totalToday(?User $user = null): int
    {
        return array_sum(array_column(static::channels($user), 'today'));
    }

    /**
     * @return Collection<int, array{
     *     id: int,
     *     type: string,
     *     type_label: string,
     *     name: string,
     *     phone: string,
     *     summary: string,
     *     status: string,
     *     status_label: string,
     *     created_at: Carbon,
     *     url: string|null,
     * }>
     */
    public static function recent(int $limit = 10, ?User $user = null): Collection
    {
        $user ??= auth()->user();
        $items = collect();

        if (static::userCanView($user, self::MODULE_JOIN)) {
            JoinApplication::query()
                ->latest()
                ->limit($limit)
                ->get()
                ->each(function (JoinApplication $row) use ($items): void {
                    $items->push([
                        'id' => $row->id,
                        'type' => 'join',
                        'type_label' => '简历投递',
                        'name' => $row->name,
                        'phone' => $row->phone,
                        'summary' => $row->position_title ?: '—',
                        'status' => $row->status,
                        'status_label' => $row->statusLabel(),
                        'created_at' => $row->created_at,
                        'url' => JoinApplicationResource::getUrl('edit', ['record' => $row]),
                    ]);
                });
        }

        if (static::userCanView($user, self::MODULE_PRODUCT)) {
            ProductConsultation::query()
                ->latest()
                ->limit($limit)
                ->get()
                ->each(function (ProductConsultation $row) use ($items): void {
                    $items->push([
                        'id' => $row->id,
                        'type' => 'product',
                        'type_label' => '产品咨询',
                        'name' => $row->name,
                        'phone' => $row->phone,
                        'summary' => $row->product_name ?: '—',
                        'status' => $row->status,
                        'status_label' => static::productStatusLabel($row->status),
                        'created_at' => $row->created_at,
                        'url' => ProductConsultationResource::getUrl('edit', ['record' => $row]),
                    ]);
                });
        }

        if (static::userCanView($user, self::MODULE_SUPPORT)) {
            SupportServiceRequest::query()
                ->latest()
                ->limit($limit)
                ->get()
                ->each(function (SupportServiceRequest $row) use ($items): void {
                    $items->push([
                        'id' => $row->id,
                        'type' => 'support',
                        'type_label' => '售后申请',
                        'name' => $row->name,
                        'phone' => $row->phone,
                        'summary' => $row->topic ?: '—',
                        'status' => $row->status,
                        'status_label' => SupportServiceRequest::statusLabel($row->status),
                        'created_at' => $row->created_at,
                        'url' => SupportServiceRequestResource::getUrl('edit', ['record' => $row]),
                    ]);
                });
        }

        return $items
            ->sortByDesc(fn (array $item) => $item['created_at']?->timestamp ?? 0)
            ->take($limit)
            ->values();
    }

    public static function userCanView(?User $user, string $module): bool
    {
        if (! $user) {
            return false;
        }

        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return true;
        }

        $permission = AdminPermissionRegistry::permissionName($module, 'view');

        if (! AdminPermissionRegistry::permissionExists($permission)) {
            return true;
        }

        return $user->hasPermission($permission);
    }

    /**
     * @return array<string, mixed>
     */
    private static function channel(
        string $key,
        string $label,
        string $description,
        int $total,
        int $pending,
        int $today,
        ?string $url,
        string $color,
    ): array {
        return compact('key', 'label', 'description', 'total', 'pending', 'today', 'url', 'color');
    }

    public static function productStatusLabel(string $status): string
    {
        return match ($status) {
            'contacted' => '已联系',
            'closed' => '已关闭',
            default => '待处理',
        };
    }
}

<?php

namespace App\Filament\Pages;

use App\Support\AdminPermissionRegistry;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = '工作台';

    protected static ?string $title = '';

    protected static ?int $navigationSort = -2;

    public function getHeading(): ?string
    {
        $user = auth()->user();

        return '欢迎回来，'.($user?->name ?: '管理员');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasPermission(
            AdminPermissionRegistry::permissionName('dashboard', 'view'),
        );
    }

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\SubmissionCollectionStats::class,
            \App\Filament\Widgets\RecentSubmissions::class,
            \App\Filament\Widgets\AdminOverviewStats::class,
            \App\Filament\Widgets\QuickManageLinks::class,
        ];
    }
}

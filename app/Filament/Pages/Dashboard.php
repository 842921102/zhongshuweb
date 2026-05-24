<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = '工作台';

    protected static ?string $title = '工作台';

    protected static ?int $navigationSort = -2;

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AdminOverviewStats::class,
            \App\Filament\Widgets\QuickManageLinks::class,
        ];
    }
}

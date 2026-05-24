<?php

namespace App\Filament\Resources\SiteNavMenus\Pages;

use App\Filament\Resources\SiteNavMenus\SiteNavMenuResource;
use App\Models\SiteNavMenu;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSiteNavMenus extends ListRecords
{
    protected static string $resource = SiteNavMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncDefaults')
                ->label('同步默认菜单')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('同步默认顶部菜单')
                ->modalDescription('将写入或更新 8 条标准菜单（中文），不会删除已添加的自定义菜单项。')
                ->action(function (): void {
                    SiteNavMenu::ensureDefaults('zh-cn');
                    Notification::make()
                        ->title('已同步默认菜单')
                        ->success()
                        ->send();
                }),
            CreateAction::make()->label('新建菜单'),
        ];
    }
}

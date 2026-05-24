<?php

namespace App\Filament\Resources\SiteStatistics\Pages;

use App\Filament\Resources\SiteStatistics\SiteStatisticResource;
use App\Models\SiteStatistic;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSiteStatistics extends ListRecords
{
    protected static string $resource = SiteStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncDefaults')
                ->label('同步默认指标')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('同步默认数据指标')
                ->modalDescription('将写入或更新 4 条首页默认指标（中文），不会删除已有自定义项。')
                ->action(function (): void {
                    SiteStatistic::ensureDefaults('zh-cn');
                    Notification::make()
                        ->title('已同步默认指标')
                        ->success()
                        ->send();
                }),
            CreateAction::make()->label('新建指标'),
        ];
    }
}

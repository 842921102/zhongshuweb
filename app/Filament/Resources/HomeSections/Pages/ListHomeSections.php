<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomeSection;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListHomeSections extends ListRecords
{
    protected static string $resource = HomeSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncDefaults')
                ->label('同步默认模块')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('同步默认首页模块')
                ->modalDescription('将写入或更新 7 个标准模块（中文）的默认配置，不会删除已存在的自定义模块。')
                ->action(function (): void {
                    HomeSection::ensureDefaults('zh-cn');
                    Notification::make()
                        ->title('已同步默认模块')
                        ->success()
                        ->send();
                }),
            CreateAction::make()->label('新建模块'),
        ];
    }
}

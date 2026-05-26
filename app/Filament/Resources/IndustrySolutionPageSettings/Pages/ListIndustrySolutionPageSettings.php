<?php

namespace App\Filament\Resources\IndustrySolutionPageSettings\Pages;

use App\Filament\Resources\IndustrySolutionPageSettings\IndustrySolutionPageSettingResource;
use App\Models\IndustrySolutionPageSetting;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListIndustrySolutionPageSettings extends ListRecords
{
    protected static string $resource = IndustrySolutionPageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('init')
                ->label('初始化中文配置')
                ->action(fn () => IndustrySolutionPageSetting::forLocale('zh-cn'))
                ->successNotificationTitle('已就绪'),
        ];
    }
}

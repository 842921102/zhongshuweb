<?php

namespace App\Filament\Resources\CasePageSettings\Pages;

use App\Filament\Resources\CasePageSettings\CasePageSettingResource;
use App\Models\CasePageSetting;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListCasePageSettings extends ListRecords
{
    protected static string $resource = CasePageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('init')
                ->label('初始化中文配置')
                ->action(fn () => CasePageSetting::forLocale('zh-cn'))
                ->successNotificationTitle('已就绪'),
        ];
    }
}

<?php

namespace App\Filament\Resources\CompanyPageSettings\Pages;

use App\Filament\Resources\CompanyPageSettings\CompanyPageSettingResource;
use App\Models\CompanyPageSetting;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListCompanyPageSettings extends ListRecords
{
    protected static string $resource = CompanyPageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ensureDefaults')
                ->label('初始化默认')
                ->action(function (): void {
                    CompanyPageSetting::forLocale('zh-cn');
                    $this->redirect(static::getResource()::getUrl('index'));
                }),
        ];
    }
}

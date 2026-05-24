<?php

namespace App\Filament\Resources\SupportPageSettings\Pages;

use App\Filament\Resources\SupportPageSettings\SupportPageSettingResource;
use App\Models\SupportPageSetting;
use Filament\Resources\Pages\ListRecords;

class ListSupportPageSettings extends ListRecords
{
    protected static string $resource = SupportPageSettingResource::class;

    public function mount(): void
    {
        SupportPageSetting::forLocale('zh-cn');
        $this->redirect(SupportPageSettingResource::getUrl('edit', ['record' => SupportPageSetting::forLocale('zh-cn')]));
    }
}

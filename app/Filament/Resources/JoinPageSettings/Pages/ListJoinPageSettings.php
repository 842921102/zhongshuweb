<?php

namespace App\Filament\Resources\JoinPageSettings\Pages;

use App\Filament\Resources\JoinPageSettings\JoinPageSettingResource;
use App\Models\JoinPageSetting;
use Filament\Resources\Pages\ListRecords;

class ListJoinPageSettings extends ListRecords
{
    protected static string $resource = JoinPageSettingResource::class;

    public function mount(): void
    {
        JoinPageSetting::forLocale('zh-cn');
        $this->redirect(JoinPageSettingResource::getUrl('edit', [
            'record' => JoinPageSetting::forLocale('zh-cn'),
        ]));
    }
}

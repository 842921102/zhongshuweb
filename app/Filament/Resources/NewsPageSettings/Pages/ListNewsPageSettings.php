<?php

namespace App\Filament\Resources\NewsPageSettings\Pages;

use App\Filament\Resources\NewsPageSettings\NewsPageSettingResource;
use App\Models\NewsPageSetting;
use Filament\Resources\Pages\ListRecords;

class ListNewsPageSettings extends ListRecords
{
    protected static string $resource = NewsPageSettingResource::class;

    public function mount(): void
    {
        NewsPageSetting::forLocale('zh-cn');
        $this->redirect(NewsPageSettingResource::getUrl('edit', [
            'record' => NewsPageSetting::forLocale('zh-cn'),
        ]));
    }
}

<?php

namespace App\Filament\Resources\ProductPageSettings\Pages;

use App\Filament\Resources\ProductPageSettings\ProductPageSettingResource;
use App\Models\ProductPageSetting;
use Filament\Resources\Pages\ListRecords;

class ListProductPageSettings extends ListRecords
{
    protected static string $resource = ProductPageSettingResource::class;

    public function mount(): void
    {
        ProductPageSetting::forLocale('zh-cn');
        $this->redirect(ProductPageSettingResource::getUrl('edit', [
            'record' => ProductPageSetting::forLocale('zh-cn'),
        ]));
    }
}

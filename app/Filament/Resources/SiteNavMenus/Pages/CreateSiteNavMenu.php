<?php

namespace App\Filament\Resources\SiteNavMenus\Pages;

use App\Filament\Resources\SiteNavMenus\SiteNavMenuResource;
use App\Models\SiteNavMenu;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteNavMenu extends CreateRecord
{
    protected static string $resource = SiteNavMenuResource::class;

    public function getTitle(): string
    {
        return '新建菜单';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['menu_type'] ?? '') === SiteNavMenu::TYPE_PRODUCT_MEGA) {
            $data['url'] = $data['url'] ?: '#home-products';
        }

        return $data;
    }
}

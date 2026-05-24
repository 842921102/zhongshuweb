<?php

namespace App\Filament\Resources\SiteNavMenus\Pages;

use App\Filament\Resources\SiteNavMenus\SiteNavMenuResource;
use App\Models\SiteNavMenu;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSiteNavMenu extends EditRecord
{
    protected static string $resource = SiteNavMenuResource::class;

    public function getTitle(): string
    {
        return '编辑菜单';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => ! $this->record->isSystem()),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['menu_type'] ?? '') === SiteNavMenu::TYPE_PRODUCT_MEGA) {
            $data['url'] = $data['url'] ?: '#home-products';
        }

        return $data;
    }
}

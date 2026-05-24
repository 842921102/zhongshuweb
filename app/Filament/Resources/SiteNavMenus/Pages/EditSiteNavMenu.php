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
                ->label('删除')
                ->requiresConfirmation()
                ->modalHeading('删除菜单')
                ->modalDescription(fn (): string => $this->record->isSystem()
                    ? '这是系统内置菜单，删除后可通过列表页「同步默认菜单」恢复。确定删除吗？'
                    : '确定删除该菜单项吗？'),
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

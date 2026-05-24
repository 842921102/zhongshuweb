<?php

namespace App\Filament\Resources\SitePartners\Pages;

use App\Filament\Resources\SitePartners\SitePartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSitePartner extends EditRecord
{
    protected static string $resource = SitePartnerResource::class;

    public function getTitle(): string
    {
        return '编辑合作伙伴';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

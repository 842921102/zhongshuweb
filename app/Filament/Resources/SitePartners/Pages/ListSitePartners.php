<?php

namespace App\Filament\Resources\SitePartners\Pages;

use App\Filament\Resources\SitePartners\SitePartnerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSitePartners extends ListRecords
{
    protected static string $resource = SitePartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('新建合作伙伴'),
        ];
    }
}

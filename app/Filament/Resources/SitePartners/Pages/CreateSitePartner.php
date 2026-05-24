<?php

namespace App\Filament\Resources\SitePartners\Pages;

use App\Filament\Resources\SitePartners\SitePartnerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSitePartner extends CreateRecord
{
    protected static string $resource = SitePartnerResource::class;

    public function getTitle(): string
    {
        return '新建合作伙伴';
    }
}

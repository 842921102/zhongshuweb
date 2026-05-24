<?php

namespace App\Filament\Resources\SiteFooterLinks\Pages;

use App\Filament\Resources\SiteFooterLinks\SiteFooterLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSiteFooterLinks extends ListRecords
{
    protected static string $resource = SiteFooterLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

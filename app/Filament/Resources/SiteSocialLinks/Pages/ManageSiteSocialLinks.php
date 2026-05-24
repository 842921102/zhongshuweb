<?php

namespace App\Filament\Resources\SiteSocialLinks\Pages;

use App\Filament\Resources\SiteSocialLinks\SiteSocialLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSiteSocialLinks extends ManageRecords
{
    protected static string $resource = SiteSocialLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

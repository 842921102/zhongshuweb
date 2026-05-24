<?php

namespace App\Filament\Resources\SiteFooterLinks\Pages;

use App\Filament\Resources\SiteFooterLinks\SiteFooterLinkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSiteFooterLink extends EditRecord
{
    protected static string $resource = SiteFooterLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

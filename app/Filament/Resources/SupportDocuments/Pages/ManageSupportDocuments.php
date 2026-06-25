<?php

namespace App\Filament\Resources\SupportDocuments\Pages;

use App\Filament\Resources\SupportDocuments\SupportDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSupportDocuments extends ManageRecords
{
    protected static string $resource = SupportDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('新增文档'),
        ];
    }
}

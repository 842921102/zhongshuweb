<?php

namespace App\Filament\Resources\CompanyCultureValues\Pages;

use App\Filament\Resources\CompanyCultureValues\CompanyCultureValueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCompanyCultureValues extends ManageRecords
{
    protected static string $resource = CompanyCultureValueResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('新增')];
    }
}

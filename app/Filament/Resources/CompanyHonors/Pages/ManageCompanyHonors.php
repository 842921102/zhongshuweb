<?php

namespace App\Filament\Resources\CompanyHonors\Pages;

use App\Filament\Resources\CompanyHonors\CompanyHonorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCompanyHonors extends ManageRecords
{
    protected static string $resource = CompanyHonorResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('新增')];
    }
}

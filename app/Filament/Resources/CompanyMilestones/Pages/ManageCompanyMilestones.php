<?php

namespace App\Filament\Resources\CompanyMilestones\Pages;

use App\Filament\Resources\CompanyMilestones\CompanyMilestoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCompanyMilestones extends ManageRecords
{
    protected static string $resource = CompanyMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('新增')];
    }
}

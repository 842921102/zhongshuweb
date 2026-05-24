<?php

namespace App\Filament\Resources\CompanyTeamMembers\Pages;

use App\Filament\Resources\CompanyTeamMembers\CompanyTeamMemberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCompanyTeamMembers extends ManageRecords
{
    protected static string $resource = CompanyTeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('新增成员')];
    }
}

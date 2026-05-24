<?php

namespace App\Filament\Resources\JoinProcessSteps\Pages;

use App\Filament\Resources\JoinProcessSteps\JoinProcessStepResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJoinProcessSteps extends ManageRecords
{
    protected static string $resource = JoinProcessStepResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('新增步骤')];
    }
}

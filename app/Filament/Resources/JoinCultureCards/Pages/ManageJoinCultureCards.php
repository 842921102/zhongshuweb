<?php

namespace App\Filament\Resources\JoinCultureCards\Pages;

use App\Filament\Resources\JoinCultureCards\JoinCultureCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJoinCultureCards extends ManageRecords
{
    protected static string $resource = JoinCultureCardResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('新增')];
    }
}

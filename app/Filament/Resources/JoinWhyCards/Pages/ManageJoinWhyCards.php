<?php

namespace App\Filament\Resources\JoinWhyCards\Pages;

use App\Filament\Resources\JoinWhyCards\JoinWhyCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJoinWhyCards extends ManageRecords
{
    protected static string $resource = JoinWhyCardResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('新增')];
    }
}

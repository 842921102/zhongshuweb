<?php

namespace App\Filament\Resources\JoinWelfareCards\Pages;

use App\Filament\Resources\JoinWelfareCards\JoinWelfareCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJoinWelfareCards extends ManageRecords
{
    protected static string $resource = JoinWelfareCardResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('新增')];
    }
}

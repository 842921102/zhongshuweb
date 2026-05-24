<?php

namespace App\Filament\Resources\JoinJobCategories\Pages;

use App\Filament\Resources\JoinJobCategories\JoinJobCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJoinJobCategories extends ManageRecords
{
    protected static string $resource = JoinJobCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('新增分类')];
    }
}

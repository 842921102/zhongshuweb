<?php

namespace App\Filament\Resources\IndustrySolutions\Pages;

use App\Filament\Resources\IndustrySolutions\IndustrySolutionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIndustrySolutions extends ListRecords
{
    protected static string $resource = IndustrySolutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('新建解决方案'),
        ];
    }
}

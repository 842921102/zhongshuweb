<?php

namespace App\Filament\Resources\SiteStatistics\Pages;

use App\Filament\Resources\SiteStatistics\SiteStatisticResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSiteStatistic extends EditRecord
{
    protected static string $resource = SiteStatisticResource::class;

    public function getTitle(): string
    {
        return '编辑数据指标';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

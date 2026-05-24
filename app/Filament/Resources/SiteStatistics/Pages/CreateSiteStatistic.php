<?php

namespace App\Filament\Resources\SiteStatistics\Pages;

use App\Filament\Resources\SiteStatistics\SiteStatisticResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteStatistic extends CreateRecord
{
    protected static string $resource = SiteStatisticResource::class;

    public function getTitle(): string
    {
        return '新建数据指标';
    }
}

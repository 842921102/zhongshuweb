<?php

namespace App\Filament\Resources\IndustrySolutionPageSettings\Pages;

use App\Filament\Resources\IndustrySolutionPageSettings\IndustrySolutionPageSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditIndustrySolutionPageSetting extends EditRecord
{
    protected static string $resource = IndustrySolutionPageSettingResource::class;

    public function getTitle(): string
    {
        return '编辑解决方案列表页设置';
    }
}

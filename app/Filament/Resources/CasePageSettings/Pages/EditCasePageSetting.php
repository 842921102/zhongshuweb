<?php

namespace App\Filament\Resources\CasePageSettings\Pages;

use App\Filament\Resources\CasePageSettings\CasePageSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditCasePageSetting extends EditRecord
{
    protected static string $resource = CasePageSettingResource::class;

    public function getTitle(): string
    {
        return '编辑案例页设置';
    }
}

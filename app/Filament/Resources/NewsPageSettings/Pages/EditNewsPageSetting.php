<?php

namespace App\Filament\Resources\NewsPageSettings\Pages;

use App\Filament\Resources\NewsPageSettings\NewsPageSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditNewsPageSetting extends EditRecord
{
    protected static string $resource = NewsPageSettingResource::class;
}

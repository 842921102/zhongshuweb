<?php

namespace App\Filament\Resources\BusinessCosSettings\Pages;

use App\Filament\Resources\BusinessCosSettings\BusinessCosSettingResource;
use App\Models\BusinessCosSetting;
use Filament\Resources\Pages\ListRecords;

class ListBusinessCosSettings extends ListRecords
{
    protected static string $resource = BusinessCosSettingResource::class;

    public function mount(): void
    {
        $record = BusinessCosSetting::instance();

        $this->redirect(BusinessCosSettingResource::getUrl('edit', ['record' => $record]));
    }
}

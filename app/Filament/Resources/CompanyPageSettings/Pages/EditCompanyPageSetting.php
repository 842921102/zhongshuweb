<?php

namespace App\Filament\Resources\CompanyPageSettings\Pages;

use App\Filament\Resources\CompanyPageSettings\CompanyPageSettingResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditCompanyPageSetting extends EditRecord
{
    protected static string $resource = CompanyPageSettingResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('预览前台')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (): string => url('/about'))
                ->openUrlInNewTab(),
        ];
    }
}

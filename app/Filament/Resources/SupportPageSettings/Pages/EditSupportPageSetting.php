<?php

namespace App\Filament\Resources\SupportPageSettings\Pages;

use App\Filament\Resources\SupportPageSettings\SupportPageSettingResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditSupportPageSetting extends EditRecord
{
    protected static string $resource = SupportPageSettingResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('预览前台')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (): string => url('/support'))
                ->openUrlInNewTab(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ProductPageSettings\Pages;

use App\Filament\Resources\ProductPageSettings\ProductPageSettingResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditProductPageSetting extends EditRecord
{
    protected static string $resource = ProductPageSettingResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (empty($data['detail_labels']) || ! is_array($data['detail_labels'])) {
            $data['detail_labels'] = \App\Services\ProductPageService::defaultDetailLabels();
        } else {
            $data['detail_labels'] = array_merge(
                \App\Services\ProductPageService::defaultDetailLabels(),
                $data['detail_labels']
            );
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('预览前台')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (): string => url('/products'))
                ->openUrlInNewTab(),
        ];
    }
}

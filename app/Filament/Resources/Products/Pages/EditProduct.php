<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview_detail')
                ->label('预览详情页')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (): string => $this->getRecord()->url())
                ->openUrlInNewTab()
                ->visible(fn (): bool => blank($this->getRecord()->detail_url) || $this->getRecord()->detail_url === '#'),
            Action::make('preview_list')
                ->label('预览列表')
                ->icon('heroicon-o-squares-2x2')
                ->url(fn (): string => route('products.index'))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}

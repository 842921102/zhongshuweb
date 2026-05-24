<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomeSection;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomeSection extends EditRecord
{
    protected static string $resource = HomeSectionResource::class;

    public function getTitle(): string
    {
        return '编辑首页模块';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => ! in_array(
                    $this->record->section_key,
                    array_keys(HomeSection::DEFINITIONS),
                    true
                )),
        ];
    }
}

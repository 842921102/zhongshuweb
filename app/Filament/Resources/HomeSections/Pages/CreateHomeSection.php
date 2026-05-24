<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomeSection;
use Filament\Resources\Pages\CreateRecord;

class CreateHomeSection extends CreateRecord
{
    protected static string $resource = HomeSectionResource::class;

    public function getTitle(): string
    {
        return '新建首页模块';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset(HomeSection::DEFINITIONS[$data['section_key'] ?? ''])) {
            $data['section_name'] = $data['section_name']
                ?: HomeSection::DEFINITIONS[$data['section_key']]['section_name'];
        }

        return $data;
    }
}

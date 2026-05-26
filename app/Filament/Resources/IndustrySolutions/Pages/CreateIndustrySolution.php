<?php

namespace App\Filament\Resources\IndustrySolutions\Pages;

use App\Filament\Resources\IndustrySolutions\IndustrySolutionResource;
use App\Support\IndustrySolutionDetailData;
use Filament\Resources\Pages\CreateRecord;

class CreateIndustrySolution extends CreateRecord
{
    protected static string $resource = IndustrySolutionResource::class;

    public function getTitle(): string
    {
        return '新建解决方案';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['detail_data']) && is_array($data['detail_data'])) {
            $data['detail_data'] = IndustrySolutionDetailData::normalizeForSave($data['detail_data']);
        }

        return $data;
    }
}

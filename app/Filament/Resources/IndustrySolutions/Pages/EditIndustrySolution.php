<?php

namespace App\Filament\Resources\IndustrySolutions\Pages;

use App\Filament\Resources\IndustrySolutions\IndustrySolutionResource;
use App\Support\IndustrySolutionDetailData;
use Filament\Resources\Pages\EditRecord;

class EditIndustrySolution extends EditRecord
{
    protected static string $resource = IndustrySolutionResource::class;

    public function getTitle(): string
    {
        return '编辑解决方案';
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['detail_data']) && is_array($data['detail_data'])) {
            $data['detail_data'] = IndustrySolutionDetailData::prepareForForm($data['detail_data']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['detail_data']) && is_array($data['detail_data'])) {
            $existing = $this->record?->detail_data;
            $data['detail_data'] = IndustrySolutionDetailData::normalizeForSave(
                $data['detail_data'],
                is_array($existing) ? $existing : null,
            );
        }

        return $data;
    }
}

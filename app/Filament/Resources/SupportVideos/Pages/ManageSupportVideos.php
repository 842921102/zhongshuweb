<?php

namespace App\Filament\Resources\SupportVideos\Pages;

use App\Filament\Resources\SupportVideos\Schemas\SupportVideoForm;
use App\Filament\Resources\SupportVideos\SupportVideoResource;
use App\Models\SupportVideo;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSupportVideos extends ManageRecords
{
    protected static string $resource = SupportVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('新增视频')
                ->using(function (array $data): SupportVideo {
                    return SupportVideo::query()->create(
                        SupportVideoForm::normalizePersistedData($data)
                    );
                }),
        ];
    }
}

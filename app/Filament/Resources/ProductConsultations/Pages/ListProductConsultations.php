<?php

namespace App\Filament\Resources\ProductConsultations\Pages;

use App\Filament\Resources\ProductConsultations\ProductConsultationResource;
use Filament\Resources\Pages\ListRecords;

class ListProductConsultations extends ListRecords
{
    protected static string $resource = ProductConsultationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

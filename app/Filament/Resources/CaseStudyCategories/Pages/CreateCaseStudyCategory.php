<?php

namespace App\Filament\Resources\CaseStudyCategories\Pages;

use App\Filament\Resources\CaseStudyCategories\CaseStudyCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCaseStudyCategory extends CreateRecord
{
    protected static string $resource = CaseStudyCategoryResource::class;

    public function getTitle(): string
    {
        return '新建案例分类';
    }
}

<?php

namespace App\Filament\Resources\CaseStudyCategories\Pages;

use App\Filament\Resources\CaseStudyCategories\CaseStudyCategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditCaseStudyCategory extends EditRecord
{
    protected static string $resource = CaseStudyCategoryResource::class;

    public function getTitle(): string
    {
        return '编辑案例分类';
    }
}

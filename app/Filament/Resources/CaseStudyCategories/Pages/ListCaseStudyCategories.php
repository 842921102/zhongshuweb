<?php

namespace App\Filament\Resources\CaseStudyCategories\Pages;

use App\Filament\Resources\CaseStudyCategories\CaseStudyCategoryResource;
use App\Models\CaseStudyCategory;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListCaseStudyCategories extends ListRecords
{
    protected static string $resource = CaseStudyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncDefaults')
                ->label('同步默认分类')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->action(function (): void {
                    CaseStudyCategory::ensureDefaults('zh-cn');
                    Notification::make()->title('已同步默认分类')->success()->send();
                }),
            CreateAction::make()->label('新建分类'),
        ];
    }
}

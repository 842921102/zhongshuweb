<?php

namespace App\Filament\Resources\ArticleCategories\Pages;

use App\Filament\Resources\ArticleCategories\ArticleCategoryResource;
use App\Models\ArticleCategory;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArticleCategories extends ListRecords
{
    protected static string $resource = ArticleCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('seedDefaults')
                ->label('初始化默认分类')
                ->color('gray')
                ->requiresConfirmation()
                ->action(fn () => ArticleCategory::ensureDefaults('zh-cn')),
            CreateAction::make()->label('新建分类'),
        ];
    }
}

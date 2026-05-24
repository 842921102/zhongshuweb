<?php

namespace App\Filament\Resources\ArticleCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ArticleCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('排序')->width(70),
                TextColumn::make('name')->label('名称')->searchable(),
                TextColumn::make('slug')->label('标识')->badge()->copyable(),
                TextColumn::make('articles_count')->label('文章数')->counts('articles')->badge(),
                TextColumn::make('locale')->label('语言')->badge(),
                IconColumn::make('is_active')->label('启用')->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('启用'),
                SelectFilter::make('locale')->label('语言')->options(['zh-cn' => '中文', 'en-us' => 'English']),
            ])
            ->recordActions([
                EditAction::make()->label('编辑'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

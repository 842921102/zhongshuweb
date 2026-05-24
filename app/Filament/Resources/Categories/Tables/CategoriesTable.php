<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label('名称')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('别名')
                    ->badge(),
                TextColumn::make('parent.name')
                    ->label('上级分类')
                    ->placeholder('一级'),
                TextColumn::make('products_count')
                    ->label('产品数')
                    ->counts('products'),
                TextColumn::make('articles_count')
                    ->label('文章数')
                    ->counts('articles')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->label('排序')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('启用')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

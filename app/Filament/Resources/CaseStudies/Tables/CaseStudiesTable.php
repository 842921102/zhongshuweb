<?php

namespace App\Filament\Resources\CaseStudies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CaseStudiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('排序')
                    ->sortable()
                    ->width(70),
                ImageColumn::make('cover_image')
                    ->label('封面')
                    ->disk('public')
                    ->height(48),
                TextColumn::make('title')
                    ->label('标题')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('category.name')
                    ->label('分类')
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('region')
                    ->label('地区')
                    ->limit(20)
                    ->toggleable(),
                TextColumn::make('locale')
                    ->label('语言')
                    ->badge(),
                IconColumn::make('is_featured')
                    ->label('精选')
                    ->boolean(),
                IconColumn::make('is_home_show')
                    ->label('首页')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('启用')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('分类')
                    ->relationship('category', 'name'),
                SelectFilter::make('locale')
                    ->label('语言')
                    ->options(['zh-cn' => '中文', 'en-us' => 'English']),
                TernaryFilter::make('is_featured')->label('精选'),
                TernaryFilter::make('is_home_show')->label('首页'),
                TernaryFilter::make('is_active')->label('启用'),
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

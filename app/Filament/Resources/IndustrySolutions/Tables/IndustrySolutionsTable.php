<?php

namespace App\Filament\Resources\IndustrySolutions\Tables;

use App\Support\Filament\ResourceTableActions;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class IndustrySolutionsTable
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
                    ->label('方案名称')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('slug')
                    ->label('别名')
                    ->limit(24)
                    ->toggleable(),
                TextColumn::make('locale')
                    ->label('语言')
                    ->badge(),
                ToggleColumn::make('is_active')->label('启用'),
            ])
            ->filters([
                SelectFilter::make('locale')
                    ->label('语言')
                    ->options(['zh-cn' => '中文', 'en-us' => 'English']),
                TernaryFilter::make('is_active')->label('启用'),
            ])
            ->recordActions(ResourceTableActions::recordActions(editLabel: '编辑'))
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }
}

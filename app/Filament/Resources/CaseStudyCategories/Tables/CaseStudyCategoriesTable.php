<?php

namespace App\Filament\Resources\CaseStudyCategories\Tables;

use App\Support\Filament\ResourceTableActions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CaseStudyCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('排序')->width(70),
                TextColumn::make('name')->label('名称')->searchable(),
                TextColumn::make('slug')->label('标识')->badge(),
                TextColumn::make('locale')->label('语言')->badge(),
                ToggleColumn::make('is_active')->label('启用'),
            ])
            ->recordActions(ResourceTableActions::recordActions(editLabel: '编辑'))
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }
}

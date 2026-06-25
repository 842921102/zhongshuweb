<?php

namespace App\Filament\Resources\SiteStatistics\Tables;

use App\Models\SiteStatistic;
use App\Support\Filament\ResourceTableActions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SiteStatisticsTable
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
                TextColumn::make('display_value')
                    ->label('前台数值')
                    ->state(fn (SiteStatistic $record): string => $record->displayValue())
                    ->weight('bold')
                    ->searchable(['value', 'unit']),
                TextColumn::make('label')
                    ->label('指标名称')
                    ->searchable()
                    ->weight('medium'),
                TextColumn::make('description')
                    ->label('备注')
                    ->limit(30)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('locale')
                    ->label('语言')
                    ->badge(),
                ToggleColumn::make('is_home_show')->label('首页'),
                ToggleColumn::make('is_active')->label('启用'),
            ])
            ->filters([
                SelectFilter::make('locale')
                    ->label('语言')
                    ->options(['zh-cn' => '中文', 'en-us' => 'English']),
                TernaryFilter::make('is_home_show')
                    ->label('首页展示'),
                TernaryFilter::make('is_active')
                    ->label('启用'),
            ])
            ->recordActions(ResourceTableActions::recordActions(editLabel: '编辑'))
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }
}

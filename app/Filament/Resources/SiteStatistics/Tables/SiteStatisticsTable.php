<?php

namespace App\Filament\Resources\SiteStatistics\Tables;

use App\Models\SiteStatistic;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
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
                IconColumn::make('is_home_show')
                    ->label('首页')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('启用')
                    ->boolean(),
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

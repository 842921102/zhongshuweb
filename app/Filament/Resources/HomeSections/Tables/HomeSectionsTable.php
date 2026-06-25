<?php

namespace App\Filament\Resources\HomeSections\Tables;

use App\Models\HomeSection;
use App\Support\Filament\ResourceTableActions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class HomeSectionsTable
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
                TextColumn::make('section_key')
                    ->label('标识')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('section_name')
                    ->label('模块名称')
                    ->searchable()
                    ->weight('medium'),
                TextColumn::make('display_title')
                    ->label('前台标题')
                    ->state(fn (HomeSection $record): string => $record->isHero()
                        ? '（首屏无标题区）'
                        : $record->displayTitle())
                    ->limit(40)
                    ->tooltip(fn (HomeSection $record): ?string => $record->isHero()
                        ? null
                        : $record->displayTitle()),
                TextColumn::make('subtitle')
                    ->label('副标题')
                    ->limit(35)
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('locale')
                    ->label('语言')
                    ->badge(),
                ToggleColumn::make('is_enabled')->label('显示'),
            ])
            ->filters([
                SelectFilter::make('locale')
                    ->label('语言')
                    ->options(['zh-cn' => '中文', 'en-us' => 'English']),
                TernaryFilter::make('is_enabled')
                    ->label('前台显示'),
            ])
            ->recordActions(ResourceTableActions::recordActions(editLabel: '编辑'))
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }
}

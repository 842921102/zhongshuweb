<?php

namespace App\Filament\Resources\SiteNavMenus\Tables;

use App\Models\SiteNavMenu;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SiteNavMenusTable
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
                TextColumn::make('menu_type')
                    ->label('类型')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => SiteNavMenu::TYPE_LABELS[$state] ?? $state)
                    ->color(fn (string $state): string => $state === SiteNavMenu::TYPE_PRODUCT_MEGA ? 'info' : 'gray'),
                TextColumn::make('label')
                    ->label('名称')
                    ->searchable()
                    ->weight('medium'),
                TextColumn::make('url')
                    ->label('链接')
                    ->limit(35)
                    ->tooltip(fn (SiteNavMenu $record): string => $record->url),
                TextColumn::make('menu_key')
                    ->label('标识')
                    ->badge()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('locale')
                    ->label('语言')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label('启用')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('menu_type')
                    ->label('类型')
                    ->options(SiteNavMenu::TYPE_LABELS),
                SelectFilter::make('locale')
                    ->label('语言')
                    ->options(['zh-cn' => '中文', 'en-us' => 'English']),
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

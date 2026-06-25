<?php

namespace App\Filament\Resources\SiteNavMenus\Tables;

use App\Models\SiteNavMenu;
use App\Support\Filament\ResourceTableActions;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
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
                ToggleColumn::make('is_active')->label('启用'),
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
            ->recordActions(ResourceTableActions::recordActions(
                configureDelete: fn (DeleteAction $action) => $action
                    ->requiresConfirmation()
                    ->modalHeading('删除菜单')
                    ->modalDescription(fn (SiteNavMenu $record): string => $record->isSystem()
                        ? '这是系统内置菜单，删除后可通过顶部「同步默认菜单」恢复。确定删除吗？'
                        : '确定删除该菜单项吗？'),
                editLabel: '编辑',
            ))
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }
}

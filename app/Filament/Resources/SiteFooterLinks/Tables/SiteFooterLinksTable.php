<?php

namespace App\Filament\Resources\SiteFooterLinks\Tables;

use App\Support\Filament\ResourceTableActions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SiteFooterLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('排序')->width(70),
                TextColumn::make('group_label')->label('分组'),
                TextColumn::make('label')->label('链接文字')->searchable(),
                TextColumn::make('url')->label('地址')->limit(40),
                ToggleColumn::make('is_active')->label('启用'),
            ])
            ->filters([
                SelectFilter::make('group_key')
                    ->label('分组')
                    ->options([
                        'products' => '产品中心',
                        'solutions' => '解决方案',
                        'about' => '关于我们',
                    ]),
            ])
            ->recordActions(ResourceTableActions::recordActions())
            ->toolbarActions(ResourceTableActions::toolbarActions());
    }
}

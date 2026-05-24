<?php

namespace App\Filament\Resources\SitePartners\Tables;

use App\Models\SitePartner;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SitePartnersTable
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
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->height(40)
                    ->state(fn (SitePartner $record): ?string => filled($record->logo)
                        ? url(media_url($record->logo))
                        : null)
                    ->checkFileExistence(false),
                TextColumn::make('name')
                    ->label('名称')
                    ->searchable()
                    ->weight('medium'),
                TextColumn::make('link')
                    ->label('链接')
                    ->limit(40)
                    ->placeholder('—')
                    ->url(fn (SitePartner $record): ?string => $record->link ?: null, shouldOpenInNewTab: true)
                    ->toggleable(),
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

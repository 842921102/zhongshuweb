<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('media_type')
                    ->label('类型')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'video' ? '视频' : '图片')
                    ->color(fn (string $state): string => $state === 'video' ? 'info' : 'success'),
                ImageColumn::make('image')
                    ->label('封面/图片')
                    ->disk('public'),
                TextColumn::make('title')
                    ->label('标题')
                    ->searchable(),
                TextColumn::make('position')
                    ->label('位置')
                    ->badge(),
                TextColumn::make('sort_order')
                    ->label('排序')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('启用')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

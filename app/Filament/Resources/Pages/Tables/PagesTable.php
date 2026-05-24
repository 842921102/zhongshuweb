<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->label('标题')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('别名')
                    ->badge(),
                TextColumn::make('sort_order')
                    ->label('排序')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('已发布')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('发布时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('发布状态'),
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

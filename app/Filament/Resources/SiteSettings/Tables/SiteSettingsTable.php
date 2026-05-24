<?php

namespace App\Filament\Resources\SiteSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('group')
            ->columns([
                TextColumn::make('label')
                    ->label('名称')
                    ->searchable(),
                TextColumn::make('key')
                    ->label('配置键')
                    ->badge(),
                TextColumn::make('group')
                    ->label('分组')
                    ->badge(),
                TextColumn::make('value')
                    ->label('值')
                    ->limit(50),
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

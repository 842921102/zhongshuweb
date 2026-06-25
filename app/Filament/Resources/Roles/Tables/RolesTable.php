<?php

namespace App\Filament\Resources\Roles\Tables;

use App\Support\Filament\ResourceTableActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ReplicateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('label')
                    ->label('角色名称')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('标识')
                    ->badge()
                    ->searchable(),
                TextColumn::make('permissions_count')
                    ->label('权限数')
                    ->counts('permissions')
                    ->sortable(),
                TextColumn::make('users_count')
                    ->label('用户数')
                    ->counts('users')
                    ->sortable(),
                IconColumn::make('is_system')
                    ->label('系统内置')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->recordActions(ResourceTableActions::recordActions(
                configureDelete: fn (DeleteAction $action) => $action
                    ->hidden(fn ($record): bool => (bool) $record->is_system),
                configureReplicate: fn (ReplicateAction $action) => $action
                    ->hidden(fn ($record): bool => (bool) $record->is_system),
            ))
            ->toolbarActions(ResourceTableActions::toolbarActions(
                configureBulkDelete: fn (DeleteBulkAction $action) => $action
                    ->before(function ($records): void {
                        if ($records->contains(fn ($record) => $record->is_system)) {
                            throw new \RuntimeException('系统内置角色不可删除。');
                        }
                    }),
            ));
    }
}

<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($records): void {
                            if ($records->contains(fn ($record) => $record->is_system)) {
                                throw new \RuntimeException('系统内置角色不可删除。');
                            }
                        }),
                ]),
            ]);
    }
}

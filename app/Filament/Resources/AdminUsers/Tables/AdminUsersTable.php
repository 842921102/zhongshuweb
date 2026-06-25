<?php

namespace App\Filament\Resources\AdminUsers\Tables;

use App\Support\Filament\ResourceTableActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdminUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id')
            ->columns([
                TextColumn::make('name')
                    ->label('姓名')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('登录邮箱')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('roles.label')
                    ->label('角色')
                    ->badge()
                    ->separator('、'),
                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions(ResourceTableActions::recordActions(
                configureDelete: fn (DeleteAction $action) => $action
                    ->hidden(fn ($record): bool => $record->id === auth()->id()),
            ))
            ->toolbarActions(ResourceTableActions::toolbarActions(
                configureBulkDelete: fn (DeleteBulkAction $action) => $action
                    ->before(function ($records): void {
                        $currentUserId = auth()->id();

                        if ($records->contains(fn ($record) => $record->id === $currentUserId)) {
                            throw new \RuntimeException('不能删除当前登录账号。');
                        }
                    }),
            ));
    }
}

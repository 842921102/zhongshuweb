<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Models\Permission;
use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('基本信息')
                    ->schema([
                        TextInput::make('label')
                            ->label('角色名称')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, callable $set, callable $get, ?Role $record): void {
                                if ($record !== null || filled($get('name'))) {
                                    return;
                                }

                                $set('name', Role::generateNameFromLabel($state ?? ''));
                            }),
                        TextInput::make('name')
                            ->label('角色标识')
                            ->required()
                            ->maxLength(255)
                            ->rule('alpha_dash')
                            ->unique(ignoreRecord: true)
                            ->helperText('英文标识，用于系统识别，如 editor、operator')
                            ->disabled(fn (?Role $record): bool => (bool) $record?->is_system),
                        Textarea::make('description')
                            ->label('描述')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(2),
                Section::make('功能权限')
                    ->description('按模块勾选该角色可执行的操作。如需新增后台账号（邮箱、密码），请前往「系统设置 → 用户管理」。')
                    ->schema([
                        Select::make('permissions')
                            ->label('权限列表')
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'label',
                                modifyQueryUsing: fn ($query) => $query->orderBy('group')->orderBy('sort_order'),
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(
                                fn (Permission $record): string => "[{$record->group}] {$record->label}",
                            )
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

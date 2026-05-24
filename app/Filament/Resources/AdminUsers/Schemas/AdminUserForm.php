<?php

namespace App\Filament\Resources\AdminUsers\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class AdminUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('基本信息')
                    ->schema([
                        TextInput::make('name')
                            ->label('姓名')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('登录邮箱')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('后台登录账号，使用邮箱 + 密码登录'),
                    ])
                    ->columns(2),
                Section::make('登录密码')
                    ->description('新建用户时必须设置密码；编辑用户时留空表示不修改原密码。')
                    ->schema([
                        TextInput::make('password')
                            ->label('登录密码')
                            ->password()
                            ->revealable()
                            ->required(fn (?User $record): bool => $record === null)
                            ->rule(Password::defaults())
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->columnSpanFull(),
                        TextInput::make('password_confirmation')
                            ->label('确认密码')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->same('password')
                            ->required(fn (?User $record, callable $get): bool => $record === null || filled($get('password')))
                            ->columnSpanFull(),
                    ]),
                Section::make('角色权限')
                    ->description('选择该用户所属角色，具体权限由角色决定。')
                    ->schema([
                        Select::make('roles')
                            ->label('所属角色')
                            ->relationship('roles', 'label')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

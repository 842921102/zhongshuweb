<?php

namespace App\Filament\Resources\SiteFooterLinks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SiteFooterLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('group_key')
                ->label('分组')
                ->required()
                ->options([
                    'products' => '产品中',
                    'solutions' => '解决方案',
                    'about' => '关于我们',
                ])
                ->live(),
            TextInput::make('group_label')
                ->label('分组标题')
                ->required()
                ->maxLength(64)
                ->default(fn ($get) => match ($get('group_key')) {
                    'products' => '产品中',
                    'solutions' => '解决方案',
                    'about' => '关于我们',
                    default => '',
                }),
            TextInput::make('label')
                ->label('链接文字')
                ->required()
                ->maxLength(120),
            TextInput::make('url')
                ->label('链接地址')
                ->maxLength(500)
                ->placeholder('/products 或 https://...'),
            TextInput::make('sort_order')
                ->label('排序')
                ->numeric()
                ->default(0),
            Toggle::make('is_active')
                ->label('启用')
                ->default(true),
            TextInput::make('locale')
                ->label('语言')
                ->default('zh-cn')
                ->maxLength(10),
        ]);
    }
}

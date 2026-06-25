<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label('显示名称')
                    ->required()
                    ->maxLength(255),
                TextInput::make('key')
                    ->label('配置键')
                    ->required()
                    ->maxLength(255)
                    ->helperText('英文标识，如 site_name、contact_phone'),
                Select::make('group')
                    ->label('分组')
                    ->options([
                        'general' => '基本信息',
                        'contact' => '联系方式',
                        'seo' => 'SEO',
                        'header' => '顶栏',
                        'footer' => '页脚',
                    ])
                    ->default('general')
                    ->required(),
                Select::make('type')
                    ->label('类型')
                    ->options([
                        'text' => '单行文本',
                        'textarea' => '多行文本',
                        'image' => '图片',
                        'url' => '链接',
                        'email' => '邮箱',
                    ])
                    ->default('text')
                    ->required()
                    ->live(),
                Textarea::make('value')
                    ->label('值')
                    ->rows(3)
                    ->visible(fn ($get) => in_array($get('type'), ['text', 'textarea', 'url', 'email'], true))
                    ->columnSpanFull(),
                FileUpload::make('value')
                    ->label('图片')
                    ->image()
                    ->directory('settings')
                    ->disk(upload_disk())
                    ->visible(fn ($get) => $get('type') === 'image')
                    ->columnSpanFull(),
            ]);
    }
}

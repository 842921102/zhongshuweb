<?php

namespace App\Filament\Resources\CasePageSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CasePageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('顶部 Banner')
                    ->description('对应前台 /cases 顶部大图')
                    ->schema([
                        FileUpload::make('banner_image_pc')
                            ->label('Banner PC 图')
                            ->image()
                            ->directory('cases/banner')
                            ->disk('public')
                            ->columnSpanFull(),
                        FileUpload::make('banner_image_mobile')
                            ->label('Banner 手机图（可选）')
                            ->image()
                            ->directory('cases/banner')
                            ->disk('public')
                            ->columnSpanFull(),
                        TextInput::make('banner_height')
                            ->label('Banner 高度 (px)')
                            ->numeric()
                            ->default(420)
                            ->minValue(240)
                            ->maxValue(720),
                    ])
                    ->columns(2),
                Section::make('客户案例列表页')
                    ->description('Banner 上展示的标题与 SEO')
                    ->schema([
                        Select::make('locale')
                            ->label('语言')
                            ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('page_title')
                            ->label('页面标题')
                            ->required()
                            ->maxLength(120),
                        Textarea::make('page_subtitle')
                            ->label('页面副标题 / 引言')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('meta_title')
                            ->label('SEO 标题')
                            ->maxLength(160),
                        Textarea::make('meta_description')
                            ->label('SEO 描述')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

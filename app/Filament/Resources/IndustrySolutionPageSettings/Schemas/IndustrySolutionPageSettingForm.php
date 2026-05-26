<?php

namespace App\Filament\Resources\IndustrySolutionPageSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class IndustrySolutionPageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('解决方案列表页')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('顶部 Banner')
                        ->schema([
                            Section::make()
                                ->description('对应前台 /industry-cases 顶部视频或大图')
                                ->schema([
                                    TextInput::make('banner_video_url')
                                        ->label('Banner 视频 URL')
                                        ->maxLength(500)
                                        ->helperText('支持 mp4 外链；留空则使用下方图片')
                                        ->columnSpanFull(),
                                    FileUpload::make('banner_image_pc')
                                        ->label('Banner PC 图')
                                        ->image()
                                        ->directory('industry-cases/banner')
                                        ->disk('public')
                                        ->columnSpanFull(),
                                    FileUpload::make('banner_image_mobile')
                                        ->label('Banner 手机图（可选）')
                                        ->image()
                                        ->directory('industry-cases/banner')
                                        ->disk('public')
                                        ->columnSpanFull(),
                                    TextInput::make('banner_height')
                                        ->label('Banner 高度 (px)')
                                        ->numeric()
                                        ->default(640)
                                        ->minValue(320)
                                        ->maxValue(900),
                                    TextInput::make('detail_button_text')
                                        ->label('卡片默认按钮文案')
                                        ->maxLength(40)
                                        ->default('查看方案'),
                                ])
                                ->columns(2),
                        ]),
                    Tab::make('列表文案')
                        ->schema([
                            Section::make()->schema([
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
                                    ->label('页面副标题')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])->columns(2),
                        ]),
                    Tab::make('SEO')
                        ->schema([
                            Section::make()->schema([
                                TextInput::make('meta_title')
                                    ->label('SEO 标题')
                                    ->maxLength(160)
                                    ->columnSpanFull(),
                                Textarea::make('meta_description')
                                    ->label('SEO 描述')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->columnSpanFull(),
                            ]),
                        ]),
                ]),
        ]);
    }
}

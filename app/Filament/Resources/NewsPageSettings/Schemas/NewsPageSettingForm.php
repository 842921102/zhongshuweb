<?php

namespace App\Filament\Resources\NewsPageSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsPageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('新闻资讯列表页')
                ->description('对应前台 /news 顶部 Banner 与 SEO')
                ->schema([
                    Select::make('locale')->label('语言')->options(['zh-cn' => '中文'])->disabled()->dehydrated(),
                    TextInput::make('meta_title')->label('SEO 标题')->maxLength(160),
                    Textarea::make('meta_description')->label('SEO 描述')->rows(2)->columnSpanFull(),
                    TextInput::make('meta_keywords')->label('关键词')->maxLength(255),
                    FileUpload::make('banner_image_pc')->label('Banner PC 图')->image()
                        ->directory('news/banner')->disk('public')->columnSpanFull(),
                    FileUpload::make('banner_image_mobile')->label('Banner 手机图（可选）')->image()
                        ->directory('news/banner')->disk('public')->columnSpanFull(),
                    TextInput::make('banner_height')->label('Banner 高度 (px)')->numeric()->default(450)->minValue(200)->maxValue(800),
                    TextInput::make('read_more_label')->label('「阅读全文」文案')->default('阅读全文')->maxLength(40),
                    TextInput::make('all_category_label')->label('分类 Tab「全部」')->default('全部')->maxLength(40),
                ])->columns(2),
        ]);
    }
}

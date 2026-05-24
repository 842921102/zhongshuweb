<?php

namespace App\Filament\Resources\ProductPageSettings\Schemas;

use App\Services\ProductPageService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProductPageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('产品中心页面')->columnSpanFull()->tabs([
                Tab::make('SEO')->schema([
                    Section::make()->schema([
                        Select::make('locale')->label('语言')->options(['zh-cn' => '中文'])->disabled()->dehydrated(),
                        TextInput::make('meta_title')->label('SEO 标题')->maxLength(160),
                        Textarea::make('meta_description')->label('SEO 描述')->rows(2)->columnSpanFull(),
                        TextInput::make('meta_keywords')->label('关键词')->maxLength(255),
                    ])->columns(2),
                ]),
                Tab::make('列表 Banner')->schema([
                    Section::make('顶部 Banner')->description('对应前台 /products 整屏区域；详情页 Hero 使用各产品自己的封面/视频')->schema([
                        Select::make('banner_media_type')->label('媒体类型')->options([
                            'image' => '图片',
                            'video' => '视频',
                        ])->default('image')->live(),
                        FileUpload::make('banner_image_pc')->label('PC 横图')->image()
                            ->directory('products/banner')->disk('public')->maxSize(10240)
                            ->visible(fn ($get) => $get('banner_media_type') !== 'video')->columnSpanFull(),
                        FileUpload::make('banner_image_mobile')->label('手机图（可选）')->image()
                            ->directory('products/banner')->disk('public')->maxSize(10240)
                            ->visible(fn ($get) => $get('banner_media_type') !== 'video')->columnSpanFull(),
                        FileUpload::make('banner_video_url')->label('视频 MP4')->acceptedFileTypes(['video/mp4', 'video/webm'])
                            ->directory('products/banner')->disk('public')->maxSize(51200)
                            ->visible(fn ($get) => $get('banner_media_type') === 'video')->columnSpanFull(),
                        FileUpload::make('banner_video_poster')->label('视频封面图')->image()
                            ->directory('products/banner')->disk('public')->maxSize(10240)
                            ->visible(fn ($get) => $get('banner_media_type') === 'video')->columnSpanFull(),
                    ])->columns(2),
                ]),
                Tab::make('列表文案')->schema([
                    Section::make('产品列表 / 顶栏下拉')->schema([
                        TextInput::make('view_all_label')->label('顶栏下拉「查看全部」')->default('查看全部'),
                        TextInput::make('all_label')->label('分类 Tab「全部」')->default('全部'),
                        TextInput::make('detail_label')->label('卡片「查看详情」')->default('查看详情'),
                        TextInput::make('catalog_empty')->label('无产品 / 下拉无数据提示')->default('暂无产品数据')->columnSpanFull(),
                    ])->columns(2),
                ]),
                Tab::make('详情页文案')->schema([
                    Section::make('产品详情页通用文案')->description('产品名称、参数等内容在「产品列表」中按产品维护')->schema([
                        KeyValue::make('detail_labels')
                            ->label('')
                            ->keyLabel('键（勿改）')
                            ->valueLabel('前台显示文案')
                            ->addable(false)
                            ->deletable(false)
                            ->editableKeys(false)
                            ->default(fn () => ProductPageService::defaultDetailLabels())
                            ->columnSpanFull(),
                    ]),
                ]),
            ]),
        ]);
    }
}

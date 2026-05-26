<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('产品编辑')->columnSpanFull()->tabs([
                Tab::make('基本信息')->schema([
                    Section::make()->schema([
                        Select::make('category_id')
                            ->label('所属分类')
                            ->relationship(
                                name: 'category',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->orderBy('parent_id')->orderBy('sort_order'),
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->parent_id
                                ? ($record->parent?->name.' / '.$record->name)
                                : $record->name)
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')->label('产品名称')->required()->maxLength(120),
                        TextInput::make('slug')->label('URL 别名')->maxLength(120)
                            ->helperText('前台地址：/products/{别名}，留空保存时自动生成'),
                        TextInput::make('model_no')->label('型号')->maxLength(80),
                        TextInput::make('subtitle')->label('副标题')->maxLength(255),
                        Textarea::make('summary')->label('简介')->rows(4)->columnSpanFull(),
                        TextInput::make('meta_title')->label('SEO 标题')->maxLength(160),
                        Textarea::make('meta_description')->label('SEO 描述')->rows(2)->maxLength(500)->columnSpanFull(),
                    ])->columns(2),
                ]),
                Tab::make('列表展示')->schema([
                    Section::make('产品列表卡片')->description('对应前台 /products 网格卡片')->schema([
                        FileUpload::make('cover_image')->label('封面图（PC / 默认）')->image()->directory('products')->disk('public'),
                        FileUpload::make('cover_image_mobile')->label('封面图（手机端，可选）')->image()->directory('products')->disk('public'),
                        FileUpload::make('home_image')->label('列表大图（PC / 默认）')->image()->directory('products')->disk('public')
                            ->helperText('优先用于列表与卡片展示'),
                        FileUpload::make('home_image_mobile')->label('列表大图（手机端，可选）')->image()->directory('products')->disk('public'),
                        Repeater::make('metrics')
                            ->label('卡片参数（数值 + 说明）')
                            ->schema([
                                TextInput::make('value')->label('数值')->required()->placeholder('1000mm'),
                                TextInput::make('label')->label('说明')->required()->placeholder('割草幅宽'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('添加参数')
                            ->columnSpanFull(),
                    ])->columns(2),
                ]),
                Tab::make('详情 Banner')->schema([
                    Section::make()->description('与产品列表 Banner 组件相同；数据来自本页，非「产品中心页面」设置')->schema([
                        FileUpload::make('hero_poster')->label('封面图')->image()->directory('products/hero')->disk('public')->columnSpanFull(),
                        FileUpload::make('hero_video')->label('视频 MP4')->acceptedFileTypes(['video/mp4', 'video/webm'])
                            ->directory('products/hero')->disk('public')->maxSize(51200)->columnSpanFull(),
                    ]),
                ]),
                Tab::make('产品展示')->schema([
                    FileUpload::make('showcase_images')->label('轮播图')->image()->multiple()->reorderable()
                        ->directory('products/showcase')->disk('public')->columnSpanFull()
                        ->helperText('详情页「产品展示」区块；未上传时使用列表大图'),
                ]),
                Tab::make('图文详情')->schema([
                    FileUpload::make('detail_hero_image')->label('详情主图（PC / 默认）')->image()->directory('products/detail')->disk('public'),
                    FileUpload::make('detail_hero_image_mobile')->label('详情主图（手机端，可选）')->image()->directory('products/detail')->disk('public')->columnSpanFull(),
                    FileUpload::make('detail_gallery')->label('详情图集')->image()->multiple()->reorderable()
                        ->directory('products/detail')->disk('public')->columnSpanFull(),
                    Repeater::make('detail_features')->label('亮点')->schema([
                        Textarea::make('text')->label('说明')->rows(2)->required(),
                    ])->defaultItems(0)->addActionLabel('添加亮点')->columnSpanFull(),
                ]),
                Tab::make('技术参数')->schema([
                    Repeater::make('spec_groups')->label('参数分组（Tab）')->schema([
                        TextInput::make('label')->label('分组名')->required()->placeholder('电气参数'),
                        TextInput::make('key')->label('Key（可选）')->placeholder('spec-电气参数')
                            ->helperText('留空则自动生成'),
                        Repeater::make('rows')->label('参数项')->schema([
                            TextInput::make('label')->label('项目')->required(),
                            TextInput::make('value')->label('数值')->required(),
                        ])->columns(2)->defaultItems(0)->addActionLabel('添加参数'),
                    ])->defaultItems(0)->addActionLabel('添加分组')->columnSpanFull(),
                    FileUpload::make('spec_document')->label('完整资料 PDF')->acceptedFileTypes(['application/pdf'])
                        ->directory('products/docs')->disk('public')->columnSpanFull(),
                ]),
                Tab::make('购车权益')->schema([
                    Section::make()->schema([
                        TextInput::make('rights_content.title')->label('标题'),
                        TextInput::make('rights_content.time_range')->label('活动时间'),
                        Textarea::make('rights_content.notice')->label('说明')->rows(2)->columnSpanFull(),
                        Repeater::make('rights_content.highlights')->label('重点权益（块）')->schema([
                            TextInput::make('text')->label('文案')->required(),
                        ])->defaultItems(0)->columnSpanFull(),
                        TextInput::make('rights_content.list_title')->label('列表标题')->default('服务权益'),
                        Repeater::make('rights_content.list_items')->label('权益列表')->schema([
                            TextInput::make('text')->label('文案')->required(),
                        ])->defaultItems(0)->columnSpanFull(),
                    ])->columns(2),
                ]),
                Tab::make('咨询联系')->schema([
                    FileUpload::make('contact_bg_image')->label('联系区背景（PC / 默认）')->image()
                        ->directory('products/contact')->disk('public'),
                    FileUpload::make('contact_bg_image_mobile')->label('联系区背景（手机端，可选）')->image()
                        ->directory('products/contact')->disk('public')->columnSpanFull(),
                ]),
                Tab::make('发布设置')->schema([
                    Section::make()->schema([
                        TextInput::make('detail_url')->label('外链详情（可选）')->maxLength(500)
                            ->helperText('填写后「查看详情」跳转此外链，不使用本站详情页'),
                        TextInput::make('sort_order')->label('排序')->numeric()->default(0),
                        Toggle::make('is_active')->label('上架')->default(true),
                        Toggle::make('is_home_show')->label('首页展示'),
                        Toggle::make('is_home_featured')->label('首页精选'),
                        Select::make('locale')->label('语言')->options(['zh-cn' => '中文'])->default('zh-cn'),
                    ])->columns(2),
                ]),
            ]),
        ]);
    }
}

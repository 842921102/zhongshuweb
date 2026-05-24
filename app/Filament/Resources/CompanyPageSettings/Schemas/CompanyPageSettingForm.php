<?php

namespace App\Filament\Resources\CompanyPageSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

/**
 * 表单 Tab 顺序与前台 /about 区块自上而下一致。
 */
class CompanyPageSettingForm
{
    private const UPLOAD_DISK = 'public';

    private const DIR_HERO_IMAGE = 'company-about/images';

    private const DIR_HERO_VIDEO = 'company-about/videos';

    private const DIR_INTRO = 'company-about/intro';

    private const DIR_STATION = 'company-about/stations';

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('关于我们页面')
                    ->id('company-page-settings')
                    ->persistTab()
                    ->scrollable(true)
                    ->contained(false)
                    ->columnSpanFull()
                    ->tabs([
                        static::seoTab(),
                        static::bannerTab(),
                        static::profileTab(),
                        static::capabilitiesTab(),
                        static::stationsTab(),
                        static::timelineTab(),
                        static::cultureTab(),
                        static::honorsTab(),
                        static::teamTab(),
                    ]),
            ]);
    }

    private static function seoTab(): Tab
    {
        return Tab::make('① SEO')
            ->icon('heroicon-o-magnifying-glass')
            ->schema([
                Section::make('搜索引擎')
                    ->description('对应页面 <head> 中的 title 与 description')
                    ->schema([
                        Select::make('locale')->label('语言')->options(['zh-cn' => '中文'])->disabled()->dehydrated(),
                        TextInput::make('meta_title')->label('SEO 标题')->maxLength(160),
                        Textarea::make('meta_description')->label('SEO 描述')->rows(3)->maxLength(500)->columnSpanFull(),
                    ]),
            ]);
    }

    private static function bannerTab(): Tab
    {
        return Tab::make('② 顶部 Banner')
            ->icon('heroicon-o-photo')
            ->schema([
                Section::make('全宽 Banner 图/视频')
                    ->description('前台首屏整宽展示，无绿色滤镜，请上传横图 1920×1080 或 MP4 视频')
                    ->schema([
                        Select::make('hero_media_type')
                            ->label('媒体类型')
                            ->options(['image' => '图片', 'video' => '视频'])
                            ->default('image')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('hero_media_url', null)),
                        static::heroImageUpload(),
                        static::heroVideoUpload(),
                        static::heroPosterUpload(),
                    ]),
            ]);
    }

    private static function profileTab(): Tab
    {
        return Tab::make('③ 公司简介')
            ->icon('heroicon-o-document-text')
            ->schema([
                Section::make('标题与正文')
                    ->description('Banner 下方白底区块：居中标题 + 左栏文案与数据')
                    ->schema([
                        TextInput::make('intro_eyebrow')->label('英文眉标')->maxLength(80)->default('About Us'),
                        TextInput::make('intro_title')->label('主标题')->required()->maxLength(120)->default('关于众鼠'),
                        Textarea::make('intro_body')->label('简介正文（多段请空行分隔）')->rows(8)->columnSpanFull(),
                    ]),
                Section::make('数据指标')
                    ->schema([
                        Repeater::make('global_metrics')
                            ->label('')
                            ->schema([
                                TextInput::make('value')->label('数值')->required()->placeholder('20+'),
                                TextInput::make('label')->label('说明')->required()->placeholder('服务城市'),
                            ])
                            ->columns(2)
                            ->defaultItems(3)
                            ->addActionLabel('添加指标')
                            ->columnSpanFull(),
                    ]),
                Section::make('右侧配图与浮层文案')
                    ->schema([
                        FileUpload::make('intro_side_image')
                            ->label('右侧配图')
                            ->image()
                            ->directory(static::DIR_INTRO)
                            ->disk(static::UPLOAD_DISK)
                            ->visibility('public')
                            ->maxSize(10240)
                            ->imagePreviewHeight('160')
                            ->helperText('留空则使用 Banner 同一张图')
                            ->columnSpanFull(),
                        TextInput::make('intro_visual_title')->label('配图浮层标题')->maxLength(120)->default('从设备到运营'),
                        Textarea::make('intro_visual_text')->label('配图浮层说明')->rows(2)->columnSpanFull(),
                    ]),
            ]);
    }

    private static function capabilitiesTab(): Tab
    {
        return Tab::make('④ 核心能力')
            ->icon('heroicon-o-squares-2x2')
            ->schema([
                Section::make('区块标题')
                    ->schema([
                        TextInput::make('capabilities_eyebrow')->label('英文眉标')->maxLength(120)->default('Core Capabilities'),
                        TextInput::make('capabilities_title')->label('主标题')->maxLength(200),
                        Textarea::make('capabilities_lead')->label('导语')->rows(2)->columnSpanFull(),
                    ]),
                Section::make('能力卡片（6 项）')
                    ->schema([
                        Repeater::make('capabilities')
                            ->label('')
                            ->schema([
                                TextInput::make('icon')->label('图标字')->maxLength(4)->required()->placeholder('研'),
                                TextInput::make('title')->label('标题')->required()->maxLength(80),
                                Textarea::make('text')->label('说明')->required()->rows(3)->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(6)
                            ->addActionLabel('添加能力项')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function stationsTab(): Tab
    {
        return Tab::make('⑤ 服务站')
            ->icon('heroicon-o-map-pin')
            ->schema([
                Section::make('Tab 区标题')
                    ->description('左 Tab + 右标题，下方为左右分栏卡片（同前台服务站模块）')
                    ->schema([
                        TextInput::make('global_station_eyebrow')->label('右上眉标')->maxLength(120),
                        TextInput::make('global_station_heading')->label('右上主标题')->maxLength(200),
                    ]),
                Section::make('服务站列表')
                    ->schema([
                        Repeater::make('service_stations')
                            ->label('')
                            ->schema([
                                TextInput::make('tab_label')->label('Tab 名称')->required(),
                                FileUpload::make('image')->label('左侧配图')->image()->directory(static::DIR_STATION)->disk(static::UPLOAD_DISK)->visibility('public')->maxSize(10240)->imagePreviewHeight('120'),
                                TextInput::make('badge')->label('角标')->placeholder('高新技术企业'),
                                TextInput::make('title')->label('站名标题')->required(),
                                Textarea::make('description')->label('简介')->rows(3)->columnSpanFull(),
                                TextInput::make('phone')->label('联系电话'),
                            ])
                            ->columns(2)
                            ->defaultItems(2)
                            ->addActionLabel('添加服务站')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function timelineTab(): Tab
    {
        return Tab::make('⑥ 发展路径')
            ->icon('heroicon-o-clock')
            ->schema([
                Section::make('区块文案')
                    ->description('具体里程碑请在左侧菜单「发展历程」中按年份维护；前台展示排序靠前的 4 条')
                    ->schema([
                        TextInput::make('timeline_eyebrow')->label('英文眉标')->maxLength(120)->default('Development Path'),
                        TextInput::make('timeline_title')->label('主标题')->maxLength(120)->default('发展历程'),
                        Textarea::make('timeline_lead')->label('导语')->rows(3)->columnSpanFull(),
                    ]),
            ]);
    }

    private static function cultureTab(): Tab
    {
        return Tab::make('⑦ 企业文化')
            ->icon('heroicon-o-heart')
            ->schema([
                Section::make('区块文案')
                    ->description('五条文化要义请在「企业文化条目」中维护（Tab 名=要义，如：重本）')
                    ->schema([
                        TextInput::make('culture_eyebrow')->label('右上眉标')->maxLength(120),
                        TextInput::make('culture_title')->label('右上主标题')->maxLength(120)->default('企业文化'),
                        Textarea::make('culture_mission_text')->label('Tab 下方导语')->rows(3)->columnSpanFull(),
                    ]),
            ]);
    }

    private static function honorsTab(): Tab
    {
        return Tab::make('⑧ 品牌荣誉')
            ->icon('heroicon-o-trophy')
            ->schema([
                Section::make('区块文案')
                    ->description('证书/奖牌图片请在「品牌荣誉」中上传，每条须含图片')
                    ->schema([
                        TextInput::make('honors_eyebrow')->label('英文眉标')->maxLength(120),
                        TextInput::make('honors_title')->label('主标题')->maxLength(120)->default('品牌荣誉'),
                        TextInput::make('honors_subtitle')->label('副标题（图片墙上方）')->maxLength(200),
                    ]),
            ]);
    }

    private static function teamTab(): Tab
    {
        return Tab::make('⑨ 团队介绍')
            ->icon('heroicon-o-user-group')
            ->schema([
                Section::make('区块文案')
                    ->description('成员请在「团队成员」维护；开启「置顶」者显示为大卡片')
                    ->schema([
                        TextInput::make('team_eyebrow')->label('英文眉标')->maxLength(80)->default('Our Team'),
                        TextInput::make('team_title')->label('主标题')->maxLength(120)->default('团队介绍'),
                        TextInput::make('team_tech_subtitle')->label('技术团队小标题')->maxLength(200),
                    ]),
            ]);
    }

    private static function heroImageUpload(): FileUpload
    {
        return FileUpload::make('hero_media_url')
            ->label('Banner 图片')
            ->image()
            ->directory(static::DIR_HERO_IMAGE)
            ->disk(static::UPLOAD_DISK)
            ->visibility('public')
            ->maxSize(10240)
            ->imagePreviewHeight('220')
            ->openable()
            ->downloadable()
            ->visible(fn (Get $get): bool => ($get('hero_media_type') ?? 'image') === 'image')
            ->required(fn (Get $get): bool => ($get('hero_media_type') ?? 'image') === 'image')
            ->columnSpanFull();
    }

    private static function heroVideoUpload(): FileUpload
    {
        return FileUpload::make('hero_media_url')
            ->label('Banner 视频')
            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/x-m4v'])
            ->directory(static::DIR_HERO_VIDEO)
            ->disk(static::UPLOAD_DISK)
            ->visibility('public')
            ->maxSize(51200)
            ->openable()
            ->downloadable()
            ->visible(fn (Get $get): bool => $get('hero_media_type') === 'video')
            ->required(fn (Get $get): bool => $get('hero_media_type') === 'video')
            ->columnSpanFull();
    }

    private static function heroPosterUpload(): FileUpload
    {
        return FileUpload::make('hero_poster_url')
            ->label('视频封面')
            ->image()
            ->directory(static::DIR_HERO_IMAGE)
            ->disk(static::UPLOAD_DISK)
            ->visibility('public')
            ->maxSize(10240)
            ->imagePreviewHeight('160')
            ->visible(fn (Get $get): bool => $get('hero_media_type') === 'video')
            ->columnSpanFull();
    }
}

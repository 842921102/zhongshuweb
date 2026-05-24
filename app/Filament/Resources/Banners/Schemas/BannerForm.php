<?php

namespace App\Filament\Resources\Banners\Schemas;

use App\Models\Banner;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BannerForm
{
    private const IMAGE_PC_SIZE = '推荐尺寸：1920×1080 px（16:9），格式 JPG/PNG/WebP，单张 ≤10MB';

    private const IMAGE_MOBILE_SIZE = '推荐尺寸：750×1334 px 或 1080×1920 px（竖版），格式 JPG/PNG/WebP，单张 ≤10MB';

    private const VIDEO_PC_SIZE = '推荐尺寸：1920×1080 px（16:9），格式 MP4/WebM，单个 ≤50MB';

    private const VIDEO_MOBILE_SIZE = '推荐尺寸：1080×1920 px（竖版），格式 MP4/WebM，单个 ≤50MB';

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->label('标题')
                    ->required()
                    ->maxLength(255)
                    ->helperText('仅用于后台识别，不在前台 Banner 区域展示'),
                TextInput::make('subtitle')
                    ->label('副标题')
                    ->maxLength(255)
                    ->helperText('仅用于后台备注，不在前台展示'),
                Select::make('media_type')
                    ->label('媒体类型')
                    ->options([
                        Banner::TYPE_IMAGE => '图片',
                        Banner::TYPE_VIDEO => '视频',
                    ])
                    ->default(Banner::TYPE_IMAGE)
                    ->required()
                    ->live(),
                FileUpload::make('image')
                    ->label(fn (Get $get): string => $get('media_type') === Banner::TYPE_VIDEO
                        ? '视频封面（PC）'
                        : 'PC 图片')
                    ->image()
                    ->required(fn (Get $get): bool => $get('media_type') === Banner::TYPE_IMAGE)
                    ->directory('banners')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(10240)
                    ->imagePreviewHeight('200')
                    ->openable()
                    ->downloadable()
                    ->helperText(fn (Get $get): string => $get('media_type') === Banner::TYPE_VIDEO
                        ? self::IMAGE_PC_SIZE.'；视频加载前显示的封面'
                        : self::IMAGE_PC_SIZE)
                    ->columnSpanFull(),
                FileUpload::make('image_mobile')
                    ->label(fn (Get $get): string => $get('media_type') === Banner::TYPE_VIDEO
                        ? '视频封面（移动端）'
                        : '移动端图片')
                    ->image()
                    ->directory('banners')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(10240)
                    ->imagePreviewHeight('200')
                    ->openable()
                    ->helperText(self::IMAGE_MOBILE_SIZE.'；留空则使用 PC 图/封面'),
                FileUpload::make('video')
                    ->label('PC 视频')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/x-m4v'])
                    ->required(fn (Get $get): bool => $get('media_type') === Banner::TYPE_VIDEO)
                    ->visible(fn (Get $get): bool => $get('media_type') === Banner::TYPE_VIDEO)
                    ->directory('banners/videos')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(51200)
                    ->openable()
                    ->downloadable()
                    ->helperText(self::VIDEO_PC_SIZE)
                    ->columnSpanFull(),
                FileUpload::make('video_mobile')
                    ->label('移动端视频')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/x-m4v'])
                    ->visible(fn (Get $get): bool => $get('media_type') === Banner::TYPE_VIDEO)
                    ->directory('banners/videos')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(51200)
                    ->openable()
                    ->helperText(self::VIDEO_MOBILE_SIZE.'；留空则使用 PC 视频'),
                Select::make('locale')
                    ->label('语言')
                    ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                    ->default('zh-cn'),
                TextInput::make('link')
                    ->label('跳转链接')
                    ->url()
                    ->maxLength(255),
                TextInput::make('button_text')
                    ->label('按钮文字')
                    ->maxLength(255)
                    ->helperText('填写后在前台 Banner 右下角展示按钮'),
                Select::make('position')
                    ->label('展示位置')
                    ->options([
                        'home' => '首页',
                        'about' => '关于我们',
                        'services' => '产品服务',
                    ])
                    ->default('home')
                    ->required(),
                TextInput::make('sort_order')
                    ->label('排序')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('启用')
                    ->default(true),
                DateTimePicker::make('starts_at')
                    ->label('开始时间'),
                DateTimePicker::make('ends_at')
                    ->label('结束时间'),
            ]);
    }
}

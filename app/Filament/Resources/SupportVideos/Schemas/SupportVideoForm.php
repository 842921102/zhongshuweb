<?php

namespace App\Filament\Resources\SupportVideos\Schemas;

use App\Models\SupportVideo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SupportVideoForm
{
    public const SOURCE_UPLOAD = 'upload';

    public const SOURCE_EXTERNAL = 'external';

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('视频信息')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('标题')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull(),
                        Select::make('video_source')
                            ->label('视频来源')
                            ->options([
                                self::SOURCE_UPLOAD => '本地上传',
                                self::SOURCE_EXTERNAL => '外链地址',
                            ])
                            ->default(self::SOURCE_UPLOAD)
                            ->live()
                            ->afterStateHydrated(function (Select $component, $state, ?SupportVideo $record): void {
                                if ($record !== null && self::isExternalUrl($record->video_url)) {
                                    $component->state(self::SOURCE_EXTERNAL);
                                }
                            }),
                        FileUpload::make('video_file')
                            ->label('视频 MP4 / WebM')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/x-m4v'])
                            ->directory('support/videos')
                            ->disk(upload_disk())
                            ->visibility('public')
                            ->maxSize(51200)
                            ->openable()
                            ->downloadable()
                            ->visible(fn (Get $get): bool => ($get('video_source') ?? self::SOURCE_UPLOAD) !== self::SOURCE_EXTERNAL)
                            ->required(fn (Get $get, ?SupportVideo $record): bool => ($get('video_source') ?? self::SOURCE_UPLOAD) !== self::SOURCE_EXTERNAL
                                && blank($record?->video_url))
                            ->helperText('推荐 MP4，单个文件 ≤50MB')
                            ->columnSpanFull(),
                        TextInput::make('video_external_url')
                            ->label('视频外链')
                            ->url()
                            ->maxLength(500)
                            ->visible(fn (Get $get): bool => ($get('video_source') ?? self::SOURCE_UPLOAD) === self::SOURCE_EXTERNAL)
                            ->required(fn (Get $get): bool => ($get('video_source') ?? self::SOURCE_UPLOAD) === self::SOURCE_EXTERNAL)
                            ->helperText('支持 https 直链 MP4/WebM')
                            ->columnSpanFull(),
                        FileUpload::make('cover_image')
                            ->label('封面图（PC / 默认）')
                            ->image()
                            ->directory('support/videos')
                            ->disk(upload_disk())
                            ->visibility('public')
                            ->maxSize(10240)
                            ->imagePreviewHeight('120')
                            ->openable()
                            ->helperText('推荐 16:9，留空时前台仅显示播放按钮'),
                        FileUpload::make('cover_image_mobile')
                            ->label('封面图（手机端，可选）')
                            ->image()
                            ->directory('support/videos')
                            ->disk(upload_disk())
                            ->visibility('public')
                            ->maxSize(10240)
                            ->imagePreviewHeight('120')
                            ->helperText('留空则小屏使用 PC 封面'),
                        TextInput::make('duration_label')
                            ->label('时长')
                            ->placeholder('02:00')
                            ->maxLength(20),
                        TextInput::make('tag')
                            ->label('标签')
                            ->placeholder('操作教程')
                            ->maxLength(80),
                    ]),
                Section::make('发布设置')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->numeric()
                            ->default(0),
                        Select::make('locale')
                            ->label('语言')
                            ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                            ->default('zh-cn')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('启用')
                            ->default(true),
                    ]),
            ]);
    }

    /** @return array<string, mixed> */
    public static function fillFormState(SupportVideo $record): array
    {
        $data = $record->attributesToArray();
        $data['video_source'] = self::isExternalUrl($record->video_url)
            ? self::SOURCE_EXTERNAL
            : self::SOURCE_UPLOAD;

        if ($data['video_source'] === self::SOURCE_EXTERNAL) {
            $data['video_external_url'] = $record->video_url;
        } else {
            $data['video_file'] = $record->video_url;
        }

        return $data;
    }

    /** @param  array<string, mixed>  $data */
    public static function normalizePersistedData(array $data): array
    {
        $source = $data['video_source'] ?? self::SOURCE_UPLOAD;
        unset($data['video_source']);

        if ($source === self::SOURCE_EXTERNAL) {
            $data['video_url'] = trim((string) ($data['video_external_url'] ?? ''));
        } elseif (filled($data['video_file'] ?? null)) {
            $data['video_url'] = $data['video_file'];
        }

        unset($data['video_file'], $data['video_external_url']);

        return $data;
    }

    public static function isExternalUrl(?string $url): bool
    {
        return filled($url)
            && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'));
    }
}

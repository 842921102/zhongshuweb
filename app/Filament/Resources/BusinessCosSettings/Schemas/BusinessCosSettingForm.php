<?php

namespace App\Filament\Resources\BusinessCosSettings\Schemas;

use App\Models\BusinessCosSetting;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BusinessCosSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('启用与用途')
                ->description('保存后生效。启用「后台上传走 COS」后，Filament 新上传的文件将写入腾讯云（需先测试连接成功）。')
                ->schema([
                    Toggle::make('is_enabled')
                        ->label('启用腾讯云 COS')
                        ->default(false)
                        ->live(),
                    Toggle::make('use_for_uploads')
                        ->label('后台上传走 COS')
                        ->helperText('开启后管理后台 FileUpload 使用 cos 磁盘（通过 upload_disk()）')
                        ->default(false)
                        ->visible(fn ($get) => $get('is_enabled')),
                ]),
            Section::make('访问密钥')
                ->description('在腾讯云控制台「访问管理 → API 密钥」创建。留空表示不修改已保存的密钥。')
                ->schema([
                    TextInput::make('secret_id')
                        ->label('SecretId')
                        ->password()
                        ->revealable()
                        ->maxLength(255)
                        ->dehydrated(fn ($state) => filled($state)),
                    TextInput::make('secret_key')
                        ->label('SecretKey')
                        ->password()
                        ->revealable()
                        ->maxLength(255)
                        ->dehydrated(fn ($state) => filled($state)),
                ])
                ->columns(2),
            Section::make('存储桶')
                ->schema([
                    Select::make('region')
                        ->label('地域')
                        ->options(BusinessCosSetting::REGIONS)
                        ->required()
                        ->searchable()
                        ->default('ap-guangzhou'),
                    TextInput::make('bucket')
                        ->label('Bucket 名称')
                        ->placeholder('fanfou 或 fanfou-1256529290')
                        ->required()
                        ->maxLength(128)
                        ->helperText('短名 fanfou，或直接填 COS 完整桶名 fanfou-1256529290（后者可不再填 AppId）'),
                    TextInput::make('app_id')
                        ->label('AppId')
                        ->placeholder('1256529290')
                        ->maxLength(32)
                        ->helperText('纯数字账号 ID（勿填 SecretId）。Bucket 已含 -1256529290 时可留空'),
                    Placeholder::make('full_bucket_preview')
                        ->label('解析后的完整桶名')
                        ->content(function ($get, ?BusinessCosSetting $record): string {
                            $preview = new BusinessCosSetting([
                                'bucket' => $get('bucket') ?? $record?->bucket,
                                'app_id' => $get('app_id') ?? $record?->app_id,
                            ]);

                            $full = $preview->fullBucketName();
                            $errors = $preview->configurationErrors();

                            if ($errors !== []) {
                                return $full.'（'.implode(' ', $errors).'）';
                            }

                            return $full !== '' ? $full : '—';
                        })
                        ->columnSpanFull(),
                    TextInput::make('path_prefix')
                        ->label('路径前缀')
                        ->default('uploads')
                        ->maxLength(120)
                        ->helperText('对象键前缀，如 uploads'),
                    TextInput::make('cdn_domain')
                        ->label('自定义 CDN 域名（可选）')
                        ->placeholder('https://cdn.example.com')
                        ->url()
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('连接状态')
                ->schema([
                    Placeholder::make('test_status_display')
                        ->label('最近测试')
                        ->content(fn (?BusinessCosSetting $record): string => match ($record?->last_test_status) {
                            BusinessCosSetting::TEST_OK => '成功',
                            BusinessCosSetting::TEST_FAIL => '失败',
                            default => '尚未测试',
                        }),
                    Placeholder::make('test_time_display')
                        ->label('测试时间')
                        ->content(fn (?BusinessCosSetting $record): string => $record?->last_tested_at?->format('Y-m-d H:i:s') ?? '—'),
                    Placeholder::make('test_message_display')
                        ->label('测试说明')
                        ->content(fn (?BusinessCosSetting $record): string => $record?->last_test_message ?: '—')
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->visible(fn (?BusinessCosSetting $record) => $record !== null),
        ]);
    }
}

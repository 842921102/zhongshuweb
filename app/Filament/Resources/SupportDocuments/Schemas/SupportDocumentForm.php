<?php

namespace App\Filament\Resources\SupportDocuments\Schemas;

use App\Models\SupportDocument;
use App\Models\SupportPageSetting;
use App\Support\FileSize;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class SupportDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('文档信息')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('文档名称')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull(),
                        Select::make('category')
                            ->label('分类')
                            ->options(fn (Get $get): array => self::categoryOptions($get('locale') ?: 'zh-cn'))
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('label')->label('分类名称')->required()->maxLength(80),
                            ])
                            ->createOptionUsing(function (array $data, Get $get): string {
                                $name = trim((string) ($data['label'] ?? ''));

                                if ($name === '') {
                                    return '';
                                }

                                self::appendCategory($name, $get('locale') ?: 'zh-cn');

                                return $name;
                            })
                            ->helperText('选项来自「页面设置 → 文档分类」，也可在此新建'),
                        TextInput::make('version')
                            ->label('版本')
                            ->placeholder('v1.0')
                            ->maxLength(40),
                        TextInput::make('published_label')
                            ->label('发布月份')
                            ->placeholder('2026-04')
                            ->default(fn (): string => now()->format('Y-m'))
                            ->maxLength(40),
                        TextInput::make('page_count')
                            ->label('页数')
                            ->numeric()
                            ->minValue(1),
                        FileUpload::make('file_path')
                            ->label('PDF 文件')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('support/docs')
                            ->disk(upload_disk())
                            ->visibility('public')
                            ->maxSize(51200)
                            ->openable()
                            ->downloadable()
                            ->required(fn (?SupportDocument $record): bool => blank($record?->file_path))
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $label = FileSize::labelForStoragePath($state);

                                if ($label !== null) {
                                    $set('file_size_label', $label);
                                }
                            })
                            ->helperText('支持 PDF，单个文件 ≤50MB')
                            ->columnSpanFull(),
                        TextInput::make('file_size_label')
                            ->label('文件大小展示')
                            ->placeholder('自动读取')
                            ->maxLength(40)
                            ->helperText('上传 PDF 后自动填充，也可手动修改'),
                    ]),
                Section::make('发布设置')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->numeric()
                            ->default(0)
                            ->helperText('数字越小越靠前'),
                        Select::make('locale')
                            ->label('语言')
                            ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                            ->default('zh-cn')
                            ->required()
                            ->live(),
                        Toggle::make('is_active')
                            ->label('启用')
                            ->default(true)
                            ->helperText('关闭后前台不展示'),
                    ]),
            ]);
    }

    /** @return array<string, string> */
    public static function categoryOptions(string $locale = 'zh-cn'): array
    {
        return collect(SupportPageSetting::forLocale($locale)->categoryFilters())
            ->mapWithKeys(fn (string $category): array => [$category => $category])
            ->all();
    }

    public static function appendCategory(string $name, string $locale = 'zh-cn'): void
    {
        $settings = SupportPageSetting::forLocale($locale);
        $categories = $settings->categoryFilters();

        if (in_array($name, $categories, true)) {
            return;
        }

        $settings->doc_categories = array_values(array_merge($categories, [$name]));
        $settings->save();
    }
}

<?php

namespace App\Filament\Resources\HomeSections\Schemas;

use App\Models\HomeSection;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class HomeSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('模块信息')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('section_key')
                            ->label('模块标识')
                            ->options(static::sectionKeyOptions())
                            ->required()
                            ->disabled(fn (?HomeSection $record): bool => $record !== null)
                            ->dehydrated()
                            ->helperText(fn (?HomeSection $record): ?string => $record
                                ? '标识创建后不可修改'
                                : '选择与前台区块对应的唯一标识')
                            ->live(),
                        TextInput::make('section_name')
                            ->label('模块名称')
                            ->required()
                            ->maxLength(100)
                            ->helperText('后台列表显示用'),
                        Select::make('locale')
                            ->label('语言')
                            ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                            ->default('zh-cn')
                            ->required()
                            ->unique(
                                ignoreRecord: true,
                                modifyRuleUsing: fn (Unique $rule, Get $get) => $rule
                                    ->where('section_key', $get('section_key'))
                            ),
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->numeric()
                            ->default(0)
                            ->helperText('数字越小越靠前'),
                        Toggle::make('is_enabled')
                            ->label('前台显示')
                            ->default(true)
                            ->inline(false)
                            ->columnSpanFull(),
                        TextInput::make('background_color')
                            ->label('背景色（可选）')
                            ->placeholder('#F8FAFC')
                            ->maxLength(20)
                            ->columnSpanFull(),
                    ]),
                Section::make('区块标题文案')
                    ->columnSpanFull()
                    ->description(fn (Get $get): string => self::hintForKey($get('section_key')))
                    ->visible(fn (Get $get): bool => $get('section_key') !== 'hero')
                    ->schema([
                        TextInput::make('title')
                            ->label('主标题')
                            ->maxLength(120)
                            ->helperText('显示在区块标题前半部分'),
                        TextInput::make('title_highlight')
                            ->label('高亮标题')
                            ->maxLength(120)
                            ->helperText('通常用绿色高亮显示的后半部分，如「解决方案」'),
                        Textarea::make('subtitle')
                            ->label('副标题')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),
                Section::make('关于我们配图')
                    ->columnSpanFull()
                    ->description('仅用于首页「关于我们」模块的大图与浮层文案，与「关于我们页面」设置相互独立。')
                    ->visible(fn (Get $get): bool => $get('section_key') === 'about')
                    ->schema([
                        FileUpload::make('visual_image')
                            ->label('配图（PC / 默认）')
                            ->image()
                            ->directory('home-sections/about')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(10240)
                            ->imagePreviewHeight('160')
                            ->helperText('推荐横版大图，1920×900 左右；留空则回退到「关于我们页面」中的配图'),
                        FileUpload::make('visual_image_mobile')
                            ->label('配图（手机端，可选）')
                            ->image()
                            ->directory('home-sections/about')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(10240)
                            ->imagePreviewHeight('160')
                            ->columnSpanFull(),
                        Textarea::make('visual_text')
                            ->label('浮层简介')
                            ->rows(4)
                            ->maxLength(1000)
                            ->helperText('显示在图片上的白色文案；留空则回退到关于页「公司简介」摘要')
                            ->columnSpanFull(),
                        TextInput::make('visual_button_label')
                            ->label('按钮文案')
                            ->maxLength(40)
                            ->default('了解我们')
                            ->placeholder('了解我们'),
                        TextInput::make('visual_button_url')
                            ->label('按钮链接')
                            ->maxLength(500)
                            ->placeholder('/about')
                            ->helperText('可填 /about 或完整 URL'),
                    ]),
                Section::make('首屏 Banner 说明')
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => $get('section_key') === 'hero')
                    ->schema([
                        Textarea::make('subtitle')
                            ->label('备注')
                            ->default('首屏无独立标题区，请在「轮播图」中维护图片/视频与按钮。此处仅控制该屏是否显示。')
                            ->disabled()
                            ->dehydrated(false)
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /** @return array<string, string> */
    protected static function sectionKeyOptions(): array
    {
        $options = [];
        foreach (HomeSection::DEFINITIONS as $key => $def) {
            $options[$key] = $def['section_name'].' ('.$key.')';
        }

        return $options;
    }

    protected static function hintForKey(?string $key): string
    {
        if ($key && isset(HomeSection::DEFINITIONS[$key])) {
            return HomeSection::DEFINITIONS[$key]['hint'];
        }

        return '设置前台该模块顶部的大标题与副标题';
    }
}

<?php

namespace App\Filament\Resources\HomeSections\Schemas;

use App\Models\HomeSection;
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

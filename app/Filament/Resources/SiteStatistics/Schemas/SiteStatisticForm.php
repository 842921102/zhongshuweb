<?php

namespace App\Filament\Resources\SiteStatistics\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteStatisticForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('指标内容')
                    ->columnSpanFull()
                    ->columns(2)
                    ->description('显示在首页「合作伙伴」区块下方的数据条带，如 12+、91.3%')
                    ->schema([
                        TextInput::make('value')
                            ->label('数值')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('12+')
                            ->helperText('前台大号数字，如 12+、110+、91.3%'),
                        TextInput::make('unit')
                            ->label('单位（可选）')
                            ->maxLength(20)
                            ->placeholder('%')
                            ->helperText('若数值已含单位可留空；否则与数值拼接显示'),
                        TextInput::make('label')
                            ->label('指标名称')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('合作伙伴')
                            ->helperText('显示在数值下方的说明文字'),
                        Textarea::make('description')
                            ->label('备注')
                            ->rows(2)
                            ->maxLength(255)
                            ->helperText('仅后台备注，不在前台展示')
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('排序')
                            ->numeric()
                            ->default(0)
                            ->helperText('数字越小越靠前'),
                        Select::make('locale')
                            ->label('语言')
                            ->options(['zh-cn' => '中文', 'en-us' => 'English'])
                            ->default('zh-cn')
                            ->required(),
                        Toggle::make('is_home_show')
                            ->label('首页展示')
                            ->default(true)
                            ->inline(false),
                        Toggle::make('is_active')
                            ->label('启用')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}

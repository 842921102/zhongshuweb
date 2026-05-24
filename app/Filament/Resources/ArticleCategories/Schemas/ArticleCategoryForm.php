<?php

namespace App\Filament\Resources\ArticleCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleCategoryForm
{
    /** @return array<int, \Filament\Forms\Components\Component> */
    public static function quickFields(): array
    {
        return [
            TextInput::make('name')->label('分类名称')->required()->maxLength(80),
            TextInput::make('slug')->label('URL 标识')->maxLength(80),
            Select::make('locale')->label('语言')->options(['zh-cn' => '中文'])->default('zh-cn'),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('分类信息')
                ->description('用于新闻列表角标与前台分类筛选')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('分类名称')
                        ->required()
                        ->maxLength(80)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                            if (filled($get('slug'))) {
                                return;
                            }
                            $slug = Str::slug($state ?? '');
                            if ($slug !== '') {
                                $set('slug', $slug);
                            }
                        }),
                    TextInput::make('slug')
                        ->label('URL 标识')
                        ->maxLength(80)
                        ->helperText('前台筛选参数 ?category=slug；留空将自动生成')
                        ->unique(ignoreRecord: true),
                    Textarea::make('description')
                        ->label('说明')
                        ->rows(2)
                        ->columnSpanFull(),
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
}
